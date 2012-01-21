<?php
/**
* SERVIRED per Arluk Software, S.L.
*basado en notify.php del core de virtuemart
*
*/

if ($_POST) {
   header("HTTP/1.0 200 OK");
   
    global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,
    $mosConfig_mailfrom, $mosConfig_fromname;

        /*** access Joomla's configuration file ***/
        $my_path = dirname(__FILE__);

        if( file_exists($my_path."/../../../configuration.php")) {
            $absolute_path = dirname( $my_path."/../../../configuration.php" );
            require_once($my_path."/../../../configuration.php");
        }
        elseif( file_exists($my_path."/../../configuration.php")){
            $absolute_path = dirname( $my_path."/../../configuration.php" );
            require_once($my_path."/../../configuration.php");
        }
        elseif( file_exists($my_path."/configuration.php")){
            $absolute_path = dirname( $my_path."/configuration.php" );
            require_once( $my_path."/configuration.php" );
        }
        else {
            die( "Joomla Configuration File not found!" );
        }

        $absolute_path = realpath( $absolute_path );
		//$absolute_path = dirname("/usr/home/cultunet/www/configuration.php");

        // Set up the appropriate CMS framework
        if( class_exists( 'jconfig' ) ) {
			define( '_JEXEC', 1 );
			define( 'JPATH_BASE', $absolute_path );
			define( 'DS', DIRECTORY_SEPARATOR );

			// Load the framework
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

			// create the mainframe object
			$mainframe = & JFactory::getApplication( 'site' );
			
			error_log("###############JPATH: ".JPATH_BASE);

			// Initialize the framework
			$mainframe->initialise();
			
			// load system plugin group
			JPluginHelper::importPlugin( 'system' );

			// trigger the onBeforeStart events
			$mainframe->triggerEvent( 'onBeforeStart' );
			$lang =& JFactory::getLanguage();
			$mosConfig_lang = $GLOBALS['mosConfig_lang']          = strtolower( $lang->getBackwardLang() );
			// Adjust the live site path
			$mosConfig_live_site = str_replace('/administrator/components/com_virtuemart', '', JURI::base());
			$mosConfig_absolute_path = JPATH_BASE;
        } else {
        	define('_VALID_MOS', '1');
        	require_once($mosConfig_absolute_path. '/includes/joomla.php');
        	require_once($mosConfig_absolute_path. '/includes/database.php');
        	$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
        	$mainframe = new mosMainFrame($database, 'com_virtuemart', $mosConfig_absolute_path );
			error_log('#####################Framework no cargado');
        }

       // load Joomla Language File
        if (file_exists( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' )) {
            require_once( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' );
        }
        elseif (file_exists( $mosConfig_absolute_path. '/language/english.php' )) {
            require_once( $mosConfig_absolute_path. '/language/english.php' );
        }
    /*** END of Joomla config ***/ /*** VirtueMart part ***/
        require_once($mosConfig_absolute_path.'/administrator/components/com_virtuemart/virtuemart.cfg.php');
        include_once( ADMINPATH.'/compat.joomla1.5.php' );
        require_once( ADMINPATH. 'global.php' );
        require_once( CLASSPATH. 'ps_main.php' );

        /* @MWM1: Logging enhancements (file logging & composite logger). */
        $vmLogIdentifier = "notify.php";
        require_once(CLASSPATH."Log/LogInit.php");


		$debug_email_address = $mosConfig_mailfrom;
	    // restart session
	    // Constructor initializes the session!
	    $sess = new ps_session();

    /*** END VirtueMart part ***/


    //1. Finished Initialization of the notify_servired.php script"

    /**
    * Read post from Servired system and update order status
    **/
    $i = 1;

    $Ds_Date = trim(stripslashes($_POST['Ds_Date']));
    $Ds_Hour = trim(stripslashes($_POST['Ds_Hour']));
    $Ds_Amount = trim(stripslashes($_POST['Ds_Amount']));
    $Ds_Currency = trim(stripslashes($_POST['Ds_Currency']));
    $Ds_Order = trim(stripslashes($_POST['Ds_Order']));

    // Can be USD, GBP, EUR, CAD, JPY
    $Ds_MerchantCode =  trim(stripslashes($_POST['Ds_MerchantCode']));

    $Ds_Terminal = trim(stripslashes($_POST['Ds_Terminal']));
    $Ds_Signature = trim(stripslashes($_POST['Ds_Signature']));
	//respuesta de la transacción valores entre 0000 a 0099 indican que correcto
    $Ds_Response = trim(stripslashes($_POST['Ds_Response']));

    $Ds_TransactionType = trim(stripslashes($_POST['Ds_TransactionType']));
    $Ds_AuthorisationCode =  trim(stripslashes($_POST['Ds_AuthorisationCode']));

      // Get the Order Details from the database
      $qv = "SELECT `order_id`, `order_number`, `user_id`, `order_subtotal`,
                    `order_total`, `order_currency`, `order_tax`,
                    `order_shipping_tax`, `coupon_discount`, `order_discount`
                FROM `#__{vm}_orders`
                WHERE `order_id`='".intval($Ds_Order)."'";
      $db = new ps_DB;
      $db->query($qv);
      $db->next_record();
      $order_id = $db->f("order_id");
	  $user_id = $db->f("user_id");

      $d['order_id'] = $order_id;
      $d['notify_customer'] = "Y";

	 	$clave='25RF78638T034833';                     // Clave de comercio proporcionada por Sermepa.
		$name='Tienda Cultunet';                        // Nombre del comercio que sale en la transaccion.
		$code='297386963';                        // Codigo de comercio proporcionado por Sermepa.
		$terminal='2';                                      // Terminal usado.
		$currency='978';                                      // 978=Euros.
		$tipoOperacion='0';                                   // Tipo de operacion. 0=Autorizacion.
	
		
		// Calculo del hash para firmar los datos.
		$order = '000'.$order_id;
		$amount=$db->f("order_total")*100;
		$message = $amount.$order.$code.$currency.$Ds_Response.$clave;
		$signature = strtoupper(sha1($message));
		
		error_log("###############Ds_Signature: ".$Ds_Signature);
		error_log("###############signature: ".$signature);
		
     if($Ds_Signature==$signature){
			//-------------------------------------------
		  // ...read the results of the verification...
		  // If $Ds_Response esta entre 0000 y 0099 la transacción ha ido bien
		  //-------------------------------------------
		if (intval ( $Ds_Response)>=0 &&  intval ( $Ds_Response)<100 ) {

			// UPDATE THE ORDER STATUS to 'Completed'
			$d['order_status'] = "C";
			require_once ( CLASSPATH . 'ps_order.php' );
            $ps_order= new ps_order;
            $ps_order->order_status_update($d);
			
			//Conectamos con la base de datos
			$db =& JFactory::getDBO();
			
			//Query para obtener los id's de los productos que hay en el pedido	
			$query_products = "SELECT product_id FROM #__vm_order_item ";
			$query_products .= "WHERE order_id = '".$order_id."' AND order_status = 'C'";
			
			$db->setQuery($query_products);
			
			$results_products = array();
			$results_products = $db->loadRowList();
			
			//Comprobamos si alguno de los productos del pedido está relacionado 
			//con algunas de las suscripciones de RSMembership
			
			error_log('#########################$results_products: '.print_r($results_products));
			
			
			foreach($results_products as $p)
			{
				$query_memberships = "SELECT * FROM #__rsmembership_memberships ";
				$query_memberships .= "WHERE product_id = '".$p['0']."'";
				$db->setQuery($query_memberships);
				$result_mb = array();
				$result_mb = $db->loadAssoc();
				if(count($result_mb)) //añadimos el elemento
					$memberships[] = $result_mb;
			}
			
			if(count($memberships))
			{
				//Hacemos los inserts en la tabla donde se relaciona al usuario con la membresía
				foreach($memberships as $m)
				{
				  $membership_user = new stdClass;
				  $membership_user->id = '';
				  $membership_user->user_id = $user_id;
				  $membership_user->membership_id = $m['id'];
				  $membership_user->membership_start = time();
				  
				  $membership_user->membership_end = 0; //Si está a cero la membresía no caduca
				  $fecha_actual = new DateTime();
				  if($m['period_type']=='d') $fecha_fin = strtotime("+".$m['period']." day");//añadimos "period" días a la fecha actual
				  if($m['period_type']=='m') $fecha_fin = strtotime("+".$m['period']." month");//añadimos "period" meses a la fecha actual
				  if($m['period_type']=='y') $fecha_fin = strtotime("+".$m['period']." year");//añadimos "period" años a la fecha actual
				  
				  $membership_user->membership_end = $fecha_fin;
				  $membership_user->price = $m['price'];
				  $membership_user->currency = 'EUR';
				  $membership_user->status = '0';
				  $membership_user->extras = '';
				  $membership_user->notes = '';
				  $membership_user->from_transaction_id = '1';
				  $membership_user->last_transaction_id = '1';
				  $membership_user->custom_1 = '';
				  $membership_user->custom_2 = '';
				  $membership_user->custom_3 = '';
				  $membership_user->notified = '0';
				  $membership_user->published = '1';
				 
				  if (!$db->insertObject( '#__rsmembership_membership_users', $membership_user, 'id' )) {
				    echo $db->stderr();
				  }
					
				}

			}
			
			error_log('#########################$insert '.print_r($db->insertObject));
			error_log('#########################$error '.$db->stderr());
				
				
			/*$mail->From = $mosConfig_mailfrom;
			$mail->FromName = $mosConfig_fromname;
			$mail->AddAddress($debug_email_address);
			$mail->Subject = "SERVIRED IPN txn on your site";
			$mail->Body = "Hello,\n\n";
			$mail->Body .= "a SERVIRED transaction for you has been made on your website!\n";
			$mail->Body .= "-----------------------------------------------------------\n";
			$mail->Body .= "Order ID: $order_id\n";
			$mail->Body .= "Payment Status returned by SERVIRED: $Ds_Response\n";
			$mail->Body .= "Order Status Code: ".$d['order_status'];
			$mail->Send();*/
			
			//Comunicamos al usuario la operación
			$query_users = "SELECT * FROM #__users ";
			$query_users .= "WHERE id = '".$user_id."'";
			$db->setQuery($query_users);
			$result_user = array();
			$result_user = $db->loadAssoc();
			
			error_log('#########################$user_email '.$result_user['email']);
			
			$mailsubject= "Librería Cultunet - Suscripción Revista g+c";
			$mailbody= "Hola ".$result_user['name'].",\n";
			$mailbody .= "Te informamos que ya puedes acceder a la versión digital de la revista g+c haciendo clic en el siguiente enlace o copiándolo y pegándolo en tu navegador:\n\n";
			$mailbody .= "http://www.cultunet.com/index.php?option=com_rsmembership&view=mymemberships\n\n";
			$mailbody .= "Recibe un cordial saludo.\n\n";
			$sendmail = vmMail($mosConfig_mailfrom, "cultunet.com", $result_user['email'], $mailsubject, $mailbody);
			
			//Comunicamos al administrador de cultune la operación
			
			$mailsubject= "SERVIRED - Transacción Confirmada";
			$mailbody= "Hola,\n\n";
			$mailbody .= "Te informamos que SERVIRED ha confirmado una transacción en la tienda de Cultunet";
			$mailbody .= "-----------------------------------------------------------\n";
			$mailbody .= "Order ID: $order_id\n";
			$mailbody .= "Payment Status returned by SERVIRED: $Ds_Response\n";
			$mailbody .= "Order Status Code: ".$d['order_status'];
			$sendmail = vmMail($mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody);
	
		}
		else {
				$d['order_status'] = "X";
			require_once ( CLASSPATH . 'ps_order.php' );
                $ps_order= new ps_order;
                $ps_order->order_status_update($d);
				/*$mail->From = $mosConfig_mailfrom;
				$mail->FromName = $mosConfig_fromname;
				$mail->AddAddress($debug_email_address);
				$mail->Subject = "SERVIRED Transaction on your Site";
				$mail->Body = "Hello,
				An error occured while processing a SERVIRED transaction.
				----------------------------------
					Order ID: $order_id\n
					Order Status Code:$Ds_Response";
				$mail->Send();*/
				$mailsubject= "SERVIRED - Transacción Cancelada";
			$mailbody= "Hola,
				Te informamos que se ha cancelado una transacción realizada por SERVIRED en la tienda de Cultunet
				----------------------------------
					Order ID: $order_id\n
					Order Status Code:$Ds_Response";

			 vmMail($mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody );
		}
	 }
	 else{
	 	/*$mail->From = $mosConfig_mailfrom;
				$mail->FromName = $mosConfig_fromname;
				$mail->AddAddress($debug_email_address);
				$mail->Subject = "SERVIRED Transaction on your Site";
				$mail->Body = "Hello,
				Se ha recibido una transacción de SERVIRED con una firma erronea.
				----------------------------------
					Order ID: $order_id\n
					Order Status Code:$Ds_Response\n
					Cantidad recibida= $Ds_Amount.
					Cantidad pedido: $amount.
					order_servired=$Ds_Order\n
					Firma recibida=$Ds_Signature\n
					Firma generada=$signature\n
					";

				$mail->Send();*/
				$mailsubject= "SERVIRED - Transacción con firma erronea";
			$mailbody= "Atención se ha recibido una transacción de SERVIRED con una firma erronea.
				----------------------------------
					Order ID: $order_id\n
					Order Status Code:$Ds_Response\n
					Cantidad recibida= $Ds_Amount.
					Cantidad pedido: $amount.
					order_servired=$Ds_Order\n
					Firma recibida=$Ds_Signature\n
					Firma generada=$signature\n
					";

			 vmMail($mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody );
	 }

}

?>
