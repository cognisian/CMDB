<?php
/*
 *  $Id: Hydrator.php 1079 2009-04-01 19:34:43Z schalme $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Its purpose is to populate object graphs.
 *
 *
 * @package     Doctrine
 * @subpackage  Hydrate
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision: 1079 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_Hydrator extends Doctrine_Hydrator_Abstract
{
    protected $_rootAlias = null;
    /**
     * hydrateResultSet
     * parses the data returned by statement object
     *
     * This is method defines the core of Doctrine's object population algorithm
     * hence this method strives to be as fast as possible
     *
     * The key idea is the loop over the rowset only once doing all the needed operations
     * within this massive loop.
     *
     * @todo: Detailed documentation. Refactor (too long & nesting level).
     *
     * @param mixed $stmt
     * @param array $tableAliases  Array that maps table aliases (SQL alias => DQL alias)
     * @param array $aliasMap  Array that maps DQL aliases to their components
     *                         (DQL alias => array(
     *                              'table' => Table object,
     *                              'parent' => Parent DQL alias (if any),
     *                              'relation' => Relation object (if any),
     *                              'map' => Custom index to use as the key in the result (if any)
     *                              )
     *                         )
     * @return array
     */
    public function hydrateResultSet($stmt, $tableAliases)
    {
        $hydrationMode = $this->_hydrationMode;

        $this->_tableAliases = $tableAliases;

        if ($hydrationMode == Doctrine::HYDRATE_NONE) {
            return $stmt->fetchAll(PDO::FETCH_NUM);
        }

        if ($hydrationMode == Doctrine::HYDRATE_ARRAY) {
            $driver = new Doctrine_Hydrator_ArrayDriver();
        } else {
            $driver = new Doctrine_Hydrator_RecordDriver();
        }

        // Used variables during hydration
        reset($this->_queryComponents);
        $rootAlias = key($this->_queryComponents);
        $this->_rootAlias = $rootAlias;
        $rootComponentName = $this->_queryComponents[$rootAlias]['table']->getComponentName();
        // if only one component is involved we can make our lives easier
        $isSimpleQuery = count($this->_queryComponents) <= 1;
        // Holds the resulting hydrated data structure
        $result = array();
        // Holds hydration listeners that get called during hydration
        $listeners = array();
        // Lookup map to quickly discover/lookup existing records in the result
        $identifierMap = array();
        // Holds for each component the last previously seen element in the result set
        $prev = array();
        // holds the values of the identifier/primary key fields of components,
        // separated by a pipe '|' and grouped by component alias (r, u, i, ... whatever)
        // the $idTemplate is a prepared template. $id is set to a fresh template when
        // starting to process a row.
        $id = array();
        $idTemplate = array();

        $result = $driver->getElementCollection($rootComponentName);

        if ($stmt === false || $stmt === 0) {
            return $result;
        }

        // Initialize
        foreach ($this->_queryComponents as $dqlAlias => $data) {
            $componentName = $data['table']->getComponentName();
            $listeners[$componentName] = $data['table']->getRecordListener();
            $identifierMap[$dqlAlias] = array();
            $prev[$dqlAlias] = null;
            $idTemplate[$dqlAlias] = '';
        }

        $event = new Doctrine_Event(null, Doctrine_Event::HYDRATE, null);

        // Process result set
        $cache = array();
        while ($data = $stmt->fetch(Doctrine::FETCH_ASSOC)) {
            $id = $idTemplate; // initialize the id-memory
            $nonemptyComponents = array();
            $rowData = $this->_gatherRowData($data, $cache, $id, $nonemptyComponents);

            //
            // hydrate the data of the root component from the current row
            //
            $table = $this->_queryComponents[$rootAlias]['table'];
            $componentName = $table->getComponentName();
            // Ticket #1115 (getInvoker() should return the component that has addEventListener)
            $event->setInvoker($table);
            $event->set('data', $rowData[$rootAlias]);
            $listeners[$componentName]->preHydrate($event);

            $index = false;

            // Check for an existing element
            if ($isSimpleQuery || ! isset($identifierMap[$rootAlias][$id[$rootAlias]])) {
                $element = $driver->getElement($rowData[$rootAlias], $componentName);
                $event->set('data', $element);
                $listeners[$componentName]->postHydrate($event);

                // do we need to index by a custom field?
                if ($field = $this->_getCustomIndexField($rootAlias)) {
                    if (isset($result[$field])) {
                        throw new Doctrine_Hydrator_Exception("Couldn't hydrate. Found non-unique key mapping named '$field'.");
                    } else if ( ! isset($element[$field])) {
                        throw new Doctrine_Hydrator_Exception("Couldn't hydrate. Found a non-existent key named '$field'.");
                    }
                    $result[$element[$field]] = $element;
                } else {
                    $result[] = $element;
                }

                $identifierMap[$rootAlias][$id[$rootAlias]] = $driver->getLastKey($result);
            } else {
                $index = $identifierMap[$rootAlias][$id[$rootAlias]];
            }

            $driver->setLastElement($prev, $result, $index, $rootAlias, false);
            unset($rowData[$rootAlias]);

            // end hydrate data of the root component for the current row


            // $prev[$rootAlias] now points to the last element in $result.
            // now hydrate the rest of the data found in the current row, that belongs to other
            // (related) components.
            foreach ($rowData as $dqlAlias => $data) {
                $index = false;
                $map = $this->_queryComponents[$dqlAlias];
                $table = $map['table'];
                $componentName = $table->getComponentName();
                $event->set('data', $data);
                $event->setInvoker($table);
                $listeners[$componentName]->preHydrate($event);

                // It would be nice if this could be moved to the query parser but I could not find a good place to implement it
                if ( ! isset($map['parent'])) {
                    throw new Doctrine_Hydrator_Exception(
                        '"' . $componentName . '" with an alias of "' . $dqlAlias . '"' .
                        ' in your query does not reference the parent component it is related to.'
                    );
                }

                $parent = $map['parent'];
                $relation = $map['relation'];
                $relationAlias = $map['relation']->getAlias();

                $path = $parent . '.' . $dqlAlias;

                if ( ! isset($prev[$parent])) {
                    unset($prev[$dqlAlias]); // Ticket #1228
                    continue;
                }

                // check the type of the relation
                if ( ! $relation->isOneToOne() && $driver->initRelated($prev[$parent], $relationAlias)) {
                    $oneToOne = false;
                    // append element
                    if (isset($nonemptyComponents[$dqlAlias])) {
                        $indexExists = isset($identifierMap[$path][$id[$parent]][$id[$dqlAlias]]);
                        $index = $indexExists ? $identifierMap[$path][$id[$parent]][$id[$dqlAlias]] : false;
                        $indexIsValid = $index !== false ? isset($prev[$parent][$relationAlias][$index]) : false;
                        if ( ! $indexExists || ! $indexIsValid) {
                            $element = $driver->getElement($data, $componentName);
                            $event->set('data', $element);
                            $listeners[$componentName]->postHydrate($event);

                            if ($field = $this->_getCustomIndexField($dqlAlias)) {
                                if (isset($prev[$parent][$relationAlias][$element[$field]])) {
                                    throw new Doctrine_Hydrator_Exception("Couldn't hydrate. Found non-unique key mapping named '$field'.");
                                } else if ( ! isset($element[$field])) {
                                    throw new Doctrine_Hydrator_Exception("Couldn't hydrate. Found a non-existent key named '$field'.");
                                }
                                $prev[$parent][$relationAlias][$element[$field]] = $element;
                            } else {
                                $prev[$parent][$relationAlias][] = $element; 
                            }
                            $identifierMap[$path][$id[$parent]][$id[$dqlAlias]] = $driver->getLastKey($prev[$parent][$relationAlias]);                            
                        }
                        // register collection for later snapshots
                        $driver->registerCollection($prev[$parent][$relationAlias]);
                    }
                } else {
                    // 1-1 relation
                    $oneToOne = true;
                    if ( ! isset($nonemptyComponents[$dqlAlias]) && ! isset($prev[$parent][$relationAlias])) {
                        $prev[$parent][$relationAlias] = $driver->getNullPointer();
                    } else if ( ! isset($prev[$parent][$relationAlias])) {
                        $element = $driver->getElement($data, $componentName);

						// [FIX] Tickets #1205 and #1237
                        $event->set('data', $element);
                        $listeners[$componentName]->postHydrate($event);

                        $prev[$parent][$relationAlias] = $element;
                    }
                }
                if ($prev[$parent][$relationAlias] !== null) {
                    $coll =& $prev[$parent][$relationAlias];
                    $driver->setLastElement($prev, $coll, $index, $dqlAlias, $oneToOne);
                }
            }
        }

        $stmt->closeCursor();
        $driver->flush();
        //$e = microtime(true);
        //echo 'Hydration took: ' . ($e - $s) . ' for '.count($result).' records<br />';

        return $result;
    }

    /**
     * Puts the fields of a data row into a new array, grouped by the component
     * they belong to. The column names in the result set are mapped to their
     * field names during this procedure.
     *
     * @return array  An array with all the fields (name => value) of the data row,
     *                grouped by their component (alias).
     */
    protected function _gatherRowData(&$data, &$cache, &$id, &$nonemptyComponents)
    {
        $rowData = array();

        foreach ($data as $key => $value) {
            // Parse each column name only once. Cache the results. 
            if ( ! isset($cache[$key])) {
                // check ignored names. fastest solution for now. if we get more we'll start
                // to introduce a list.
                if ($key == 'DOCTRINE_ROWNUM') continue;
                $e = explode('__', $key);
                $last = strtolower(array_pop($e));
                $cache[$key]['dqlAlias'] = $this->_tableAliases[strtolower(implode('__', $e))];
                $table = $this->_queryComponents[$cache[$key]['dqlAlias']]['table'];
                $fieldName = $table->getFieldName($last);
                $cache[$key]['fieldName'] = $fieldName;
                if ($table->isIdentifier($fieldName)) {
                    $cache[$key]['isIdentifier'] = true;
                } else {
                  $cache[$key]['isIdentifier'] = false;
                }
                $type = $table->getTypeOfColumn($last);
                if ($type == 'integer' || $type == 'string') {
                    $cache[$key]['isSimpleType'] = true;
                } else {
                    $cache[$key]['type'] = $type;
                    $cache[$key]['isSimpleType'] = false;
                }
            }

            $map = $this->_queryComponents[$cache[$key]['dqlAlias']];
            $table = $map['table'];
            $dqlAlias = $cache[$key]['dqlAlias'];
            $fieldName = $cache[$key]['fieldName'];
            $agg = false;
            if (isset($this->_queryComponents[$dqlAlias]['agg'][$fieldName])) {
                $fieldName = $this->_queryComponents[$dqlAlias]['agg'][$fieldName];
                $agg = true;
            }

            if ($cache[$key]['isIdentifier']) {
                $id[$dqlAlias] .= '|' . $value;
            }

            if ($cache[$key]['isSimpleType']) {
                $rowData[$dqlAlias][$fieldName] = $value;
            } else {
                $rowData[$dqlAlias][$fieldName] = $table->prepareValue(
                        $fieldName, $value, $cache[$key]['type']);
            }

            // Ticket #1380
            // Hydrate aggregates in to the root component as well.
            // So we know that all aggregate values will always be available in the root component
            if ($agg) {
                $rowData[$this->_rootAlias][$fieldName] = $rowData[$dqlAlias][$fieldName];
            }

            if ( ! isset($nonemptyComponents[$dqlAlias]) && $value !== null) {
                $nonemptyComponents[$dqlAlias] = true;
            }
        }

        return $rowData;
    }

    /**
     * Gets the custom field used for indexing for the specified component alias.
     *
     * @return string  The field name of the field used for indexing or NULL
     *                 if the component does not use any custom field indices.
     */
    protected function _getCustomIndexField($alias)
    {
        return isset($this->_queryComponents[$alias]['map']) ? $this->_queryComponents[$alias]['map'] : null;
    }
}