<?php
/*
 *  $Id: Listener.php 1075 2009-03-31 21:16:19Z schalme $
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
 * Doctrine_AuditLog_Listener
 *
 * @package     Doctrine
 * @subpackage  AuditLog
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision: 1075 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_AuditLog_Listener extends Doctrine_Record_Listener
{
    /**
     * Instance of Doctrine_Auditlog
     *
     * @var Doctrine_AuditLog
     */
    protected $_auditLog;

    /**
     * Instantiate AuditLog listener and set the Doctrine_AuditLog instance to the class
     *
     * @param   Doctrine_AuditLog $auditLog 
     * @return  void
     */
    public function __construct(Doctrine_AuditLog $auditLog) 
    {
        $this->_auditLog = $auditLog;
    }

    /**
     * Pre insert event hook for incrementing version number
     *
     * @param   Doctrine_Event $event
     * @return  void
     */
    public function preInsert(Doctrine_Event $event)
    {
        $versionColumn = $this->_auditLog->getOption('versionColumn');

        $event->getInvoker()->set($versionColumn, 1);
    }

    /**
     * Post insert event hook which creates the new version record
     * This will only insert a version record if the auditLog is enabled
     *
     * @param   Doctrine_Event $event 
     * @return  void
     */
    public function postInsert(Doctrine_Event $event) 
    {
        if ($this->_auditLog->getOption('auditLog')) {
            $class = $this->_auditLog->getOption('className');

            $record  = $event->getInvoker();
            $version = new $class();
            $version->merge($record->toArray());
            $version->save();
        }
    }

    /**
     * Pre delete event hook deletes all related versions
     * This will only delete version records if the auditLog is enabled
     *
     * @param   Doctrine_Event $event
     * @return  void
     */
    public function preDelete(Doctrine_Event $event)
    {
        if ($this->_auditLog->getOption('auditLog')) {
	        $className = $this->_auditLog->getOption('className');
	        $versionColumn = $this->_auditLog->getOption('versionColumn');
	        $event->getInvoker()->set($versionColumn, null);

	        $q = Doctrine_Query::create();
	        foreach ((array) $this->_auditLog->getOption('table')->getIdentifier() as $id) {
	            $conditions[] = 'obj.' . $id . ' = ?';
	            $values[] = $event->getInvoker()->get($id);
	        }

	        $rows = $q->delete($className)
					  ->from($className.' obj')
					  ->where(implode(' AND ', $conditions))
					  ->execute($values);
        }
    }
  
    /**
     * Pre update event hook for inserting new version record
     * This will only insert a version record if the auditLog is enabled
     *
     * @param  Doctrine_Event $event
     * @return void
     */
    public function preUpdate(Doctrine_Event $event)
    {
        if ($this->_auditLog->getOption('auditLog')) {
            $class  = $this->_auditLog->getOption('className');
            $record = $event->getInvoker();

            $versionColumn = $this->_auditLog->getOption('versionColumn');

            $record->set($versionColumn, $this->_getNextVersion($record));

            $version = new $class();
            $version->merge($record->toArray());
            $version->save();
        }
    }

    /**
     * Get the next version number for the audit log
     *
     * @param Doctrine_Record $record 
     * @return integer $nextVersion
     */
    protected function _getNextVersion(Doctrine_Record $record)
    {
      if ($this->_auditLog->getOption('auditLog')) {
          return ($this->_auditLog->getMaxVersion($record) + 1);
      }
    }
}