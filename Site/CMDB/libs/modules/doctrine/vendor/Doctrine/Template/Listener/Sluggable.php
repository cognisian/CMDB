<?php
/*
 *  $Id: Sluggable.php 1075 2009-03-31 21:16:19Z schalme $
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
 * Easily create a slug for each record based on a specified set of fields
 *
 * @package     Doctrine
 * @subpackage  Template
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision: 1075 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_Template_Listener_Sluggable extends Doctrine_Record_Listener
{
    /**
     * Array of sluggable options
     *
     * @var string
     */
    protected $_options = array();

    /**
     * __construct
     *
     * @param string $array 
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Set the slug value automatically when a record is inserted
     *
     * @param Doctrine_Event $event 
     * @return void
     */
    public function preInsert(Doctrine_Event $event)
    {
        $record = $event->getInvoker();
        $name = $record->getTable()->getFieldName($this->_options['name']);

        if ( ! $record->$name) {
            $record->$name = $this->buildSlug($record);
        }
    }

    /**
     * Set the slug value automatically when a record is updated if the options are configured
     * to allow it
     *
     * @param Doctrine_Event $event 
     * @return void
     */
    public function preUpdate(Doctrine_Event $event)
    {
        if (false !== $this->_options['unique']) {
            $record = $event->getInvoker();
            $name = $record->getTable()->getFieldName($this->_options['name']);

            if ( ! $record->$name ||
            (false !== $this->_options['canUpdate'] &&
            array_key_exists($name, $record->getModified()))) {
                $record->$name = $this->buildSlug($record);
            }
        }
    }

    /**
     * Generate the slug for a given Doctrine_Record based on the configured options
     *
     * @param Doctrine_Record $record 
     * @return string $slug
     */
    protected function buildSlug($record)
    {
        if (empty($this->_options['fields'])) {
            if (method_exists($record, 'getUniqueSlug')) {
                $value = $record->getUniqueSlug($record);
            } else {
                $value = (string) $record;
            }
        } else {
            if ($this->_options['unique'] === true) {   
                $value = $this->getUniqueSlug($record);
            } else {  
                $value = '';
                foreach ($this->_options['fields'] as $field) {
                    $value .= $record->$field . ' ';
                } 
            }
        }

        $value =  call_user_func_array($this->_options['builder'], array($value, $record));

        return $value;
    }

    /**
     * Creates a unique slug for a given Doctrine_Record. This function enforces the uniqueness by 
     * incrementing the values with a postfix if the slug is not unique
     *
     * @param Doctrine_Record $record 
     * @return string $slug
     */
    public function getUniqueSlug($record)
    {
        $name = $record->getTable()->getFieldName($this->_options['name']);
        $slugFromFields = '';
        foreach ($this->_options['fields'] as $field) {
            $slugFromFields .= $record->$field . ' ';
        }

        $proposal = $record->$name ? $record->$name : $slugFromFields;
        $proposal =  call_user_func_array($this->_options['builder'], array($proposal, $record));
        $slug = $proposal;

        $whereString = 'r.' . $name . ' LIKE ?';
        $whereParams = array($proposal.'%');
        
        if ($record->exists()) {
            $identifier = $record->identifier();
            $whereString .= ' AND r.' . implode(' != ? AND r.', $record->getTable()->getIdentifierColumnNames()) . ' != ?';
            $whereParams = array_merge($whereParams, array_values($identifier));
        }

        foreach ($this->_options['uniqueBy'] as $uniqueBy) {
            if (is_null($record->$uniqueBy)) {
                $whereString .= ' AND r.'.$uniqueBy.' IS NULL';
            } else {
                $whereString .= ' AND r.'.$uniqueBy.' = ?';
                $whereParams[] =  $record->$uniqueBy;
            }
        }

        // Disable indexby to ensure we get all records
        $originalIndexBy = $record->getTable()->getBoundQueryPart('indexBy');
        $record->getTable()->bindQueryPart('indexBy', null);

        $query = Doctrine_Query::create()
        ->select('r.'.$name)
        ->from(get_class($record).' r')
        ->where($whereString , $whereParams)
        ->setHydrationMode(Doctrine::HYDRATE_ARRAY);

        // We need to introspect SoftDelete to check if we are not disabling unique records too
        if ($record->getTable()->hasTemplate('Doctrine_Template_SoftDelete')) {
	        $softDelete = $record->getTable()->getTemplate('Doctrine_Template_SoftDelete');
	
	        // we have to consider both situations here
            $query->addWhere('(r.' . $softDelete->getOption('name') . ' = true OR r.' . $softDelete->getOption('name') . ' IS NOT NULL OR r.' . $softDelete->getOption('name') . ' = false OR r.' . $softDelete->getOption('name') . ' IS NULL)');
        }

        $similarSlugResult = $query->execute();

        // Change indexby back
        $record->getTable()->bindQueryPart('indexBy', $originalIndexBy);

        $similarSlugs = array();
        foreach ($similarSlugResult as $key => $value) {
            $similarSlugs[$key] = $value[$name];
        }

        $i = 1;
        while (in_array($slug, $similarSlugs)) {
            $slug = call_user_func_array($this->_options['builder'], array($proposal.'-'.$i, $record)); 
            $i++;
        }

        return  $slug;
    }
}