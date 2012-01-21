<?php
class Adapters
{
	public function __construct()
	{
		//define path to adapter component
		if( !defined('NOIXACL_APADTER_PATH') ){
			define('NOIXACL_APADTER_PATH',JPATH_ADMINISTRATOR.DS.'components'.DS.'com_noixacl'.DS.'adapters');
		}
	}
	/**
	 * list adapters enabled
	 */
	public function listAdapters()
	{
		$db = & JFactory::getDBO();
		
		$sql = "SELECT "
                . "id, "
                . "title, "
                . "adapter "
                . "FROM #__noixacl_adapters "
                . "WHERE "
                . "enabled = 1 "
                . "ORDER BY "
                . "ordering ";
		
		$db->setQuery( $sql );
		$adapters = $db->loadObjectList();
		foreach($adapters as $adapter){
			$adapterFile = NOIXACL_APADTER_PATH.DS.$adapter->adapter.DS.$adapter->adapter.".xml";
                        $adapter->xmlData = $this->parseXMLInstallFile($adapterFile);
			
			if( empty($adapter->xmlData) || !$adapter->xmlData ){
				unset($adapter);		
			}
		}
		
		return $adapters;
	}

    /**
     *  Return $adapter object if exist adapter to control $component.
     *  if not returns false
     *
     * @param <type> $component
     * @return <type>
     */
    public function getAdapter($component)
    {
        $adapterLists = $this->listAdapters();

        foreach($adapterLists as $adapter){
            if( $adapter->xmlData['component'] == $component ){
                return $adapter;
                /**
                 * exit to foreach;
                 */
                break;
            }
        }

        return false;
    }

    /**
     *
     * @param <Object> $adapter
     * @param <Array> $params
     *
     */
    public function checkRule($application,$adapter,$params){
        //check if params have default keys
        if( !array_key_exists('task',$params) && !array_key_exists('params',$params) ){
            return false;
        }
        //check if array key params have not empty values
        $paramsValues = array_values($params['params']);
        
        foreach( $paramsValues as $key => $value ){
            //check if value is null or empty and return true
            if( empty($value) || is_null($value) ){
                return true;
            }
        }
        
        //get a task
        $task = strval($params['task']);
        
        if( empty($task) ){
            return true;
        }        
        
        /**
         * return all tasks from view application
         */
        $resultTask = $this->getAdapterLoadTasks($application,$adapter,$params);
        
        /**
         * return key of task
         */
        $canAccess = array_search($task,$resultTask);
        
        //return true if is intval
        if( is_int($canAccess) ){
            return true;
        }
        
        return false;
    }

    /**
     *  Returns load rules tasks from application view
     *
     * @param <String> $application
     * @param <Object> $adapter
     * @param <Array> $extraParams
     */
    public function getAdapterLoadTasks($application,$adapter,$extraParams){
        $user = & JFactory::getUser();
        $adapterFileXML = str_replace(".php",".xml",$adapter->xmlData["pathController"]);
        
        /**
         * check if exists adapter file
         */
        if( !file_exists($adapterFileXML) ){
            JError::raiseError(JText::_('noixACL'),JText::_('the adapter xml not exists'));
            return false;
        }

        /**
         *  Read the file to see if it's a valid component XML file
         */
	$xml = & JFactory::getXMLParser('Simple');

	/**
         * load XML
         */
	if ( !$xml->loadFile($adapterFileXML) ){
            JError::raiseError(JText::_('noixACL'),JText::_('Cannot load adapter xml'));
            return false;
	}
	        
        $tasks = $extraParams['task'];
        $myParams = $extraParams['params'];
        $groupName = $user->usertype;
        $viewName = $adapter->xmlData['application'][$application];
        $groupTasks = $this->loadGroupTasks( $groupName,$adapter->adapter,$viewName,$myParams);

        return $groupTasks;
    }
		
    /**
     *  Render views from array of Adapters Object
     *
     * @param <Array> $adaptersList
     *
     */
	public function renderViews($adaptersList)
	{
		$n = 0;
            /**
             * list of adapters
             */
            foreach($adaptersList as $adapter){

                if( $n == 0 ){
                    $style = "block";
                }
                else{
                    $style = "none";
                }

                /**
                 * this is a div of adapter
                 */
                echo "<div id=\"Adapter{$n}\" style=\"display:{$style};\"><fieldset><legend>{$adapter->title}</legend>";
                /**
                 * instance a JPANE
                 */
                $pane =& JPane::getInstance('tabs', array('startOffset' => 0,'startTransition' => 0));
                echo $pane->startPane( 'pane' );
                /**
                * getting views of adapter from xml
                */
                $views = $adapter->xmlData['views'];
                /**
                * list views and creating a new PANE
                */
                foreach($views as $viewName){
                    /**
                    * creating a PANE with title atribute in view
                    */
                    echo $pane->startPanel( $adapter->xmlData["title"][$viewName], 'panelAdapter'.ucfirst(strtolower($adapter->adapter)).ucfirst(strtolower($viewName)) );
                    /**
                    * this method display a adapter view
                    */
                    $this->displayAdapterView($adapter,$viewName);
                    echo $pane->endPanel();
                    
                    
                }
                
                echo $pane->endPane();
                echo "</fieldset></div>";
                $n++;
            }
    }
	
    /**
     * Display a View of Adapter
     *
     * @param <Object> $adapter
     * @param <String> $viewName
     * 
     */
	public function displayAdapterView($adapter,$viewName)
	{
            $viewFile = $adapter->xmlData['pathView'].$viewName.DS."view.html.php";

            if( !file_exists($viewFile) ){
                JError::raiseError(JText::_('noixACL'),JText::_("Cannot open file: {$viewFile}"));
                return false;
            }
            /**
             * include adapter view.html.php
             */
            include ($viewFile);

            /**
             * config path to views
             */
            $pathView = $adapter->xmlData['pathView'].$viewName;
            $arrConfigView = array( 'base_path' => $pathView,'template_path' => $pathView.DS."tmpl".DS );
            /**
             * view class name
             */
            $viewClass = $adapter->xmlData['viewsName'][$viewName];
            /**
             * instace a class of view
             */
            $adapterView = new $viewClass($arrConfigView);
            /**
             * display a view
             */
            $adapterView->display();
	}
	
    /**
     *  This method returns a Array of content in XML from Adapter
     *
     * @param <String> $path
     * @return <Array>
     *
     */
	public function parseXMLInstallFile($path)
	{
		/**
                 *  Read the file to see if it's a valid component XML file
                 */
		$xml = & JFactory::getXMLParser('Simple');
		
		/**
                 * load XML
                 */
		if ( !$xml->loadFile($path) ){
			JError::raiseError(JText::_('noixACL'),JText::_('Cannot read adapter xml file'));
			return false;
		}
		
		/**
		 * Check for a valid XML root tag.
		 *
		 * Should be 'install', but for backward compatability we will accept 'mosinstall'.
		 */
		if ( !is_object($xml->document) || ($xml->document->name() != 'install') ){
                        JError::raiseError(JText::_('noixACL'),JText::_('Invalid XML'));
			return false;
		}
		
		$data = array();

		$element = & $xml->document->name[0];
		$data['name'] = $element ? $element->data() : '';
		$data['type'] = $element ? $xml->document->attributes("type") : '';
		
		/**
                 * check type of instalation
                 */
		if( $data['type'] != 'adapter' ){
                        JError::raiseError(JText::_('noixACL'),JText::_('Invalid type instalation'));
			return false;
		}
		
		/**
                 * check files exists of adapters
                 */
		$element = & $xml->document->files[0];
		foreach( $element->children() as $filename ){
			$pathFile = NOIXACL_APADTER_PATH.DS.$data['name'].DS.$filename->data();

                        /**
                         * if is a Controller Adapter
                         */
			if( $filename->attributes("type") == 'controller' ){
				$data["pathController"] = $pathFile;
				$data["controller"]	= $data['name']."Controller";
			}

                        /**
                         * if is a Plugin Adapter
                         */
			if( $filename->attributes("type") == 'plugin' ){
				$data["pathPlugin"] = $pathFile;
				$data["pluginExecution"] = $filename->attributes("execution") ? $filename->attributes("execution") : 'componentAccess';
				$data["plugin"]	= $filename->attributes("name") ? $filename->attributes("name") : '';
			}
			
			/**
                         * check if files exists
                         */
			if( !file_exists( $pathFile ) ){
                                JError::raiseError(JText::_('noixACL'),JText::_("Adapter file {$pathFile} not exists"));
				return false;
			}
		}

                /**
                 * get the component controled by adapter
                 */
		$element = & $xml->document->component[0];
		$data['component'] = $element ? $element->data() : '';
		/**
                 * get creation date
                 */
		$element = & $xml->document->creationDate[0];
		$data['creationdate'] = $element ? $element->data() : 'Unknown';
		/**
                 * get authorname
                 */
		$element = & $xml->document->author[0];
		$data['author'] = $element ? $element->data() : 'Unknown';
		/**
                 * get copyright
                 */
		$element = & $xml->document->copyright[0];
		$data['copyright'] = $element ? $element->data() : '';
		/**
                 * get author e-mail
                 */
		$element = & $xml->document->authoremail[0];
		$data['authorEmail'] = $element ? $element->data() : '';
		/**
                 * get author url
                 */
		$element = & $xml->document->authorurl[0];
		$data['authorUrl'] = $element ? $element->data() : '';
		/**
                 * get version of adapter
                 */
		$element = & $xml->document->version[0];
		$data['version'] = $element ? $element->data() : '';
		/**
                 * get description of adapter
                 */
		$element = & $xml->document->description[0];
		$data['description'] = $element ? $element->data() : '';
		/**
                 * pathView
                 */
		$data["pathView"] = NOIXACL_APADTER_PATH.DS.$data['name'].DS."views".DS;
		/**
                 * get views
                 */
		$element = & $xml->document->views[0];
		foreach( $element->children() as $view ){
			$viewName = $view->name();
			/**
                         * name of folder inside adapter/views/
                         */
			$data['views'][] = $viewName;
			/**
                         * title of views
                         */
			$data["title"][$viewName] = $view->attributes("title");
			/**
                         * classname of view.html.php files to instance
                         */
			$data['viewsName'][$viewName] = $view->attributes("viewname");
                        /**
                         * application of view
                         */
                        $appication = $view->attributes("application") ? $view->attributes("application") : '';
                        if( !empty($appication) ){
                            $data['application'][$appication] = $viewName;
                        }
                        /**
                         * read a tasks of view
                         */
			if( $view->tasks[0] ){
				$tasks = $view->tasks[0];
				/**
                                 * get tasks by view
                                 */
				$contTask = 0;
                                 foreach( $tasks->children() as $task ){
					/**
                                         * array of all tasks
                                         */
					$data['tasks'][ $viewName ][$contTask]['value'] = $task->data();
                                        $data['tasks'][ $viewName ][$contTask]['name'] = $task->attributes("name");
                                        $contTask++;
				}
			}
		}
		
                /**
                 * returns all $data readed in XML
                 */
		return $data;
	}

    /**
     *  This method save a rule reading a configuration of xml Adapter view save
     *
     * @param <String> $adapterName
     * @param <String> $viewName
     * @param <String> $groupName
     * @param <Array> $tasks
     * @param <Array> $extraParams
     * @return <Boolean>
     * 
     */
    public function saveRule($adapterName,$viewName,$groupName='',$tasks='',$extraParams='')
    {
        $path = NOIXACL_APADTER_PATH.DS.$adapterName.DS.$adapterName.".xml";
        
        /**
         *  Read the file to see if it's a valid component XML file
         */
        $xml = & JFactory::getXMLParser('Simple');

        /**
         * load XML
         */
        if ( !$xml->loadFile($path) ){
            JError::raiseError(JText::_('noixACL'),JText::_('Cannot read adapter xml file'));
			return false;
		}

		/**
		 * Check for a valid XML root tag.
		 *
		 * Should be 'install', but for backward compatability we will accept 'mosinstall'.
		 */
		if ( !is_object($xml->document) || ($xml->document->name() != 'install') ){
                        JError::raiseError(JText::_('noixACL'),JText::_('Invalid type of instalation'));
			return false;
		}
		
  		$element = & $xml->document->views[0];
		foreach( $element->children() as $view ){
                    if( $view->name() == $viewName ){
                        $viewName = $view->name();

                        /**
                         * read a tasks of view
                         */
                        if( $view->save[0] ){
                            $saves = $view->save[0];
                            /**
                             * get tasks by view
                             */
                            $contSave = 0;
                            foreach( $saves->children() as $save ){
                                /**
                                 * array of all tasks
                                 */
                                $data[ $viewName ][$contSave]['field'] = $save->name();
                                $data[ $viewName ][$contSave]['value'] = $save->data();
                                $data[ $viewName ][$contSave]['type'] = $save->attributes("type") ? $save->attributes("type") : 'text' ;

                                $contSave++;
                    }
                }
           }
        }

        /**
         * saving view rules
         */
        foreach($data as $view){
            /**
             * default array fields to renderize views rules
             */
            $arrRule = array(
                "aco_section" => "",
                "aco_value" => "",
                "aro_section" => "users",
                "aro_value" => $groupName,
                "axo_section" => "",
                "axo_value" => ""
            );
            
            foreach($view as $key => $rule){
                /**
                 * replace arrRule key with value configurated in your save view
                 */
                switch( strtolower($rule["type"]) ){
                    case 'tasks':
                        $arrRule[ $rule["field"] ] = '$'.strval($rule["value"]);
                    break;
                    case 'var':
                        $arrRule[ $rule["field"] ] = '$'.strval($rule["value"]);

                        if( is_array($extraParams) ){
                            $findKey = '$'.strval($rule["value"]);

                            /**
                             * replace var to extraParams
                             */
                            if( array_key_exists($findKey,$extraParams) ){
                                $arrRule[ $rule["field"] ] = $extraParams[ $findKey ];
                            }
                        }
                    break;
                    case 'text':
                        $arrRule[ $rule["field"] ] = strval($rule["value"]);
                    break;
                }
            }
            
            /*
             * reading all tasks of view and create an array to save in table
             */
            foreach($tasks as $task){
                /*
                 * copy of Array structure base
                 */
                $saveRule = $arrRule;
                /**
                 * get key in your XML fields than value is $tasks
                 */
                $tasksKey = array_search('$tasks',$saveRule);

                /**
                 * replace $tasks with tasks in your view
                 */
                if( $tasksKey ){
                    $saveRule[ $tasksKey ] = $task;
                }

                if( !$this->insertRule($saveRule) ){
                    JError::raiseError(JText::_('noixACL'),JText::_("Cannot insert rule {$saveRule}"));
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * This method insert a Rule in Rules Table
     *
     * @param <Array> $arrRule
     * @return <Object>
     *
     */
     public function insertRule($arrRule)
     {
         
         $tableRule = JTable::getInstance('rules','table');
         $tableRule->bind( $arrRule );
         $tableRule->check();
         $result = $tableRule->store();

         return $result;
     }

     /**
      * This method delete ALL rules before save method.
      *
      * @param <String> $acoSection
      * @param <String> $acoValue
      * @param <String> $groupName
      * @return <Boolean>
      *
      */
    public function deleteRules($acoSection='',$acoValue='',$groupName=''){
        $db = & JFactory::getDBO();
        
        $sqlDelete = "DELETE "
                        ." FROM #__noixacl_rules "
                   ." WHERE "
                        ." aco_section = '{$acoSection}'"
                   ." AND "
                        ."aco_value = '{$acoValue}'";
                        
        //check if groupname is empty
        if( !empty($groupName) ){
            $sqlDelete .=" AND "
                    ."aro_value = '{$groupName}'";
        }
                        
        $db->setQuery( $sqlDelete );
        if( !$db->query() ){
            return false;
        }
        
        return true;
    }

    /**
     * Delete all rules from adapter returning true if success and false if fails
     *
     * @param <String> $adapterName
     * @return <Boolean>
     */
    public function deleteAdaptersRules($adapterName){
        $pathFile = NOIXACL_APADTER_PATH.DS.$adapterName.DS.$adapterName.".xml";

        $xmlAdapter = $this->parseXMLInstallFile($pathFile);
        
        if( file_exists($xmlAdapter['pathController']) ){
            include $xmlAdapter['pathController'];
            $controlAdapter = new $xmlAdapter['controller'];
            if( $controlAdapter->delete() ){
                return true;
            }
            return false;
        }

        return false;
    }

    /**
     * This method load Rules by usergroup from Database and return tasks.
     *
     * @param <String> $adapterName
     * @param <String> $viewName
     * @param <Array> $extraParams
     * @return <Array>
     *
     */
    public function loadGroupTasks($groupName,$adapterName='',$viewName='',$extraParams='')
    {
        $path = NOIXACL_APADTER_PATH.DS.$adapterName.DS.$adapterName.".xml";
        
        /**
         *  Read the file to see if it's a valid component XML file
         */
		$xml = & JFactory::getXMLParser('Simple');

		/**
                 * load XML
                 */
		if ( !$xml->loadFile($path) ){
            JError::raiseError(JText::_('noixACL'),JText::_('Cannot read adapter xml file'));
			return false;
		}

		/**
		 * Check for a valid XML root tag.
		 *
		 * Should be 'install', but for backward compatability we will accept 'mosinstall'.
		 */
		if ( !is_object($xml->document) || ($xml->document->name() != 'install') ){
                        JError::raiseError(JText::_('noixACL'),JText::_('Invalid XML'));
			return false;
		}
        
  		$element = & $xml->document->views[0];
		foreach( $element->children() as $view ){
                    if( strtolower($view->name()) == strtolower($viewName) ){
                        $viewName = $view->name();

                        /**
                         * read a tasks of view
                         */
                        if( $view->load[0] ){
                            $loads = $view->load[0];
                            /**
                             * get tasks by view
                             */
                            $contLoad = 0;
                            foreach( $loads->children() as $load ){
                                /**
                                 * array of all tasks
                                 */
                                $data[ $viewName ][$contLoad]['field'] = $load->name();
                                $data[ $viewName ][$contLoad]['value'] = $load->data();
                                $data[ $viewName ][$contLoad]['type'] = $load->attributes("type") ? $load->attributes("type") : 'text' ;

                                $contLoad++;
                    }
                }
           }
        }

        /**
         * load view rules
         */
        foreach($data as $view){
            /**
             * default array fields to renderize views rules
             */
            $arrRule = array(
                "aco_section" => "",
                "aco_value" => "",
                "aro_section" => "users",
                "aro_value" => $groupName,
                "axo_section" => "",
                "axo_value" => ""
            );

            foreach($view as $key => $rule){
                /**
                 * replace arrRule key with value configurated in your save view
                 */
                switch( strtolower($rule["type"]) ){
                    case 'tasks':
                        $arrRule[ $rule["field"] ] = '$'.strval($rule["value"]);
                    break;
                    case 'var':
                        $arrRule[ $rule["field"] ] = '$'.strval($rule["value"]);

                        if( is_array($extraParams) ){
                            $findKey = '$'.strval($rule["value"]);

                            /**
                             * replace var to extraParams
                             */
                            if( array_key_exists($findKey,$extraParams) ){
                                $arrRule[ $rule["field"] ] = $extraParams[ $findKey ];
                            }
                        }
                    break;
                    case 'text':
                        $arrRule[ $rule["field"] ] = strval($rule["value"]);
                    break;
                }
            }

            foreach($arrRule as $ruleKey => $ruleValue){
                if( $ruleKey != "field" ){
                    /**
                     * check if field is a same field in $arrRule
                     */
                    if( !is_null($arrRule["field"]) && $arrRule["field"] == $ruleKey ){
                        /**
                         * /only add if where is not empty
                         */
                        if( !empty($ruleValue) ){
                            $wheres[] = "{$ruleKey} = '{$ruleValue}'";
                        }
                    }
                    else{
                        $wheres[] = "{$ruleKey} = '{$ruleValue}'";
                    }
                }
            }

            $fieldSearch = $arrRule["field"];
            if( !array_key_exists($fieldSearch,$arrRule) ){
                $fieldText = $arrRule["field"];
                $arrRule["field"] = "'".$fieldText."' as ".$fieldText;
            }

            $sqlRule = "SELECT ".$arrRule["field"]." FROM #__noixacl_rules";
            $sqlRule .= " WHERE ". implode(' AND ',$wheres);
            
            $db = & JFactory::getDBO();
           
            $db->setQuery( $sqlRule );
            
            $rows = $db->loadObjectList();
           
            if( count($rows) ){
               if( isset($fieldText) ){
                   $arrRule["field"] = $fieldText;
               }

               foreach($rows as $r){
                   $arrResult[] = trim($r->$arrRule["field"]);
               }

               return $arrResult;
            }
            else{
               return array();
            }
        }
    }

    /**
     * This method Generate a Table of All tasks from adapter
     *
     * @param <String> $adapterName
     * @param <String> $viewName
     * @param <Array> $tasks
     * @param <String> $elemName
     * @param <Array> $checkedTasks
     * @param <Array> $checkBoxArray
     * @return <Boolean>
     *
     */
    public function renderTasks($adapterName='',$viewName='',$tasks=array(),$elemName='',$checkedTasks=array(),$checkBoxArray='')
    {
        /**
         * Check if $tasks exists
         */
        if( count($tasks) == 0 ){
            JError::raiseError(JText::_('noixACL'),JText::_("Cannot read tasks of adapter {$adapterName}"));
            return false;
        }

        echo "<div id=\"{$adapterName}{$viewName}{$elemName}Tasks\" style=\"display:none;\">";
		echo "<table class=\"adminlist\" cellspacing=\"1\">";
		echo "<thead>";
		echo "<tr>";
			echo "<th width=\"1%\">";
                $checkedAll = "";
                if( count($tasks) == count($checkedTasks) && is_array($checkedTasks)&& !empty($checkedTasks) ){
                    $checkedAll = "checked=\"checked\"";
                }
		echo "<input type=\"checkbox\" {$checkedAll} id=\"toogle{$adapterName}{$elemName}{$viewName}\" onclick=\"checkAll(". count($tasks) .",'cb{$adapterName}{$elemName}{$viewName}','toogle{$adapterName}{$elemName}{$viewName}');writeEraseAdapterTasks(". count($tasks) .",'cb{$adapterName}{$elemName}{$viewName}','toogle{$adapterName}{$elemName}{$viewName}','{$adapterName}{$viewName}{$elemName}');\">";
		echo "</th>";
		echo "<th width=\"29%\" class=\"title\">";
		echo JText::_( 'Permission' );
		echo "</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
                $contCheckboxes = 0;
		foreach( $tasks as $task ){
                    echo "<tr>";
                    echo "<td align=\"center\">";
                    $checked = '';
                    if( is_array($checkedTasks) && count($checkedTasks) > 0 && in_array($task["value"],$checkedTasks) ){
                        $checked = "checked=\"checked\"";
                    }
                    echo "<input type=\"checkbox\" {$checked} id=\"cb{$adapterName}{$elemName}{$viewName}{$contCheckboxes}\" name=\"".$adapterName."[".$viewName."]".$checkBoxArray."[tasks][".$task["value"]."]\" onclick=\"if( this.checked ){ writeAdapterTask('".$adapterName.$viewName.$elemName."','".$task["value"]."'); } else{ eraseAdapterTask('".$adapterName.$viewName.$elemName."','".$task["value"]."'); }\" value=\"".$task["value"]."\" />";
                    echo "</td>";
                    echo "<td align=\"center\">";
                    echo "<label for=\"".$task["name"]."\">";
                    echo JText::_( $task["name"] );
                    echo "</label>";
                    echo "</td>";
                    echo "</tr>";
                    $contCheckboxes++;
		}			
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
    }
}