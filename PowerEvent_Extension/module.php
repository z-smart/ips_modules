<?
    // Klassendefinition
    class PowerEvent_Extension extends IPSModule {
 
        // Der Konstruktor des Moduls
        // überschreibt den Standard Kontruktor von IPS
        public function __construct($InstanceID) {
            // Diese Zeile nicht löschen
            parent::__construct($InstanceID);
 
            // Selbsterstellter Code
        }
 
        // überschreibt die interne IPS_Create($id) Funktion
        public function Create() {
            // Diese Zeile nicht löschen.
            parent::Create();
			
			// Anlegen aller benötigten Instanz-Variabeln
			
			// Zu überwachende CurrentVar
			$this->RegisterPropertyInteger("CurrentVar", 0);
			
			// Festlegen der Zeit und Value Grenze für Mitteilungen, bzw. Zustandsänderungen
			$this->RegisterPropertyInteger("StandstillTimer", 1);
			$this->RegisterPropertyInteger("CurrentBoundary", 100);
			
			
			$this->RegisterPropertyString("MsgState1", "Zustand 1 erreicht");
			$this->RegisterPropertyInteger("EmailState1", 0);
			$this->RegisterPropertyInteger("PushState1", 0);
			$this->RegisterPropertyInteger("ScriptState1", 0);
 
			$this->RegisterPropertyString("MsgState2", "Zustand 2 erreicht");
			$this->RegisterPropertyInteger("EmailState2", 0);
			$this->RegisterPropertyInteger("PushState2", 0);
			$this->RegisterPropertyInteger("ScriptState2", 0);
 			
			$this->RegisterPropertyInteger("WebFrontInstanceID", 0); 
			$this->RegisterPropertyInteger("SmtpInstanceID", 0); 
			$this->RegisterPropertyInteger("ScriptID", 0); 

			// Timer registrieren
			$this->RegisterTimer("PowerEvent_Extension_NotifyTimer", 0, "PowerEvent_Notify(".$this->InstanceID.");");
			
			// Alte Zustands-Vars um festzustellen wann eine Änderung statt findet
			$this->RegisterPropertyInteger("LastState", 0);
			$this->RegisterPropertyInteger("LastStateChange", 0);
			
			// Zustands Vars anlegen
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern des letzten Zustands
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "PowerEvent_Extension_LastState");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern des letzten Zustands
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "PowerEvent_Extension_LastStateChange");
			SetValue($eid, 0);

			
        }
 
		public function Destroy() {
			
			//$this->UnregisterTimer("PowerEvent_Extension_NotifyTimer");

						
			parent::Destroy();
		}
 
 
        // überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            
			// Diese Zeile nicht löschen
            parent::ApplyChanges();
			
			// Eingaben prüfen und im ersten Schritt Status auf "102" setzen
			$this->setStatus(102);
			
			if ($this->ReadPropertyInteger("CurrentBoundary") < 1 || $this->ReadPropertyInteger("CurrentBoundary") > 16000) $this->SetStatus(210);
			
			if ($this->ReadPropertyInteger("StandstillTimer") < 0 || $this->ReadPropertyInteger("StandstillTimer") > 10) $this->SetStatus(211);
			
			if ((($this->ReadPropertyBoolean("PushState1") == true) || ($this->ReadPropertyBoolean("PushState2") == true)) && ($this->ReadPropertyInteger("WebFrontInstanceID") == 0)) $this->SetStatus(201);
			if ((($this->ReadPropertyBoolean("EmailState1") == true) || ($this->ReadPropertyBoolean("EmailState2") == true)) && ($this->ReadPropertyInteger("SmtpInstanceID") == 0)) $this->SetStatus(202);
			if ((($this->ReadPropertyBoolean("ScriptState1") == true) || ($this->ReadPropertyBoolean("ScriptState2") == true)) && ($this->ReadPropertyInteger("WebFrontInstanceID") == 0)) $this->SetStatus(203);
			
			if ($this->ReadPropertyInteger("CurrentVar") == 0 ) {
				$this->SetStatus(220);
			} else {
				// Event für CurrentVar anlegen und im Falle eines Updates die entsprechende Update-Function auslösen
				
				// alten Event ggf. löschen
				@IPS_DeleteEvent(IPS_GetObjectIDByName ("PowerEvent_Extension_ChangeEvent", $this->InstanceID ));
				
				// Neuen Event anlgen
				$eid = IPS_CreateEvent(0);                  										//Ausgelöstes Ereignis
				IPS_SetEventTrigger($eid, 1, $this->ReadPropertyInteger("CurrentVar") );			//Bei Änderung von Variable mit ID 15754
				IPS_SetParent($eid, $this->InstanceID );         									//Ereignis zuordnen
				IPS_SetEventScript($eid, "PowerEvent_Update(".$this->InstanceID.");");
				IPS_SetName($eid, "PowerEvent_Extension_ChangeEvent");
				IPS_SetEventActive($eid, true);
			}

        }
 
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * PowerEvent_MeineErsteEigeneFunktion($id);
        *
        */
        public function Update() {
            // Debugging Code
			IPS_LogMessage("PowerEvent_Extension_Debug","CurrentVar: " . $this->ReadPropertyInteger("CurrentVar"));
			IPS_LogMessage("PowerEvent_Extension_Debug","CurrentBoundary: " . $this->ReadPropertyInteger("CurrentBoundary"));
			IPS_LogMessage("PowerEvent_Extension_Debug","StandstillTimer: " . $this->ReadPropertyInteger("StandstillTimer"));
			IPS_LogMessage("PowerEvent_Extension_Debug","PushState1: " . $this->ReadPropertyBoolean("PushState1"));
			IPS_LogMessage("PowerEvent_Extension_Debug","EmailState1: " . $this->ReadPropertyBoolean("EmailState1"));
			IPS_LogMessage("PowerEvent_Extension_Debug","ScriptState1: " . $this->ReadPropertyBoolean("ScriptState1"));
			IPS_LogMessage("PowerEvent_Extension_Debug","PushState2: " . $this->ReadPropertyBoolean("PushState2"));
			IPS_LogMessage("PowerEvent_Extension_Debug","EmailState2: " . $this->ReadPropertyBoolean("EmailState2"));
			IPS_LogMessage("PowerEvent_Extension_Debug","ScriptState2: " . $this->ReadPropertyBoolean("ScriptState2"));
			IPS_LogMessage("PowerEvent_Extension_Debug","WebFrontInstanceID: " . $this->ReadPropertyInteger("WebFrontInstanceID"));
			IPS_LogMessage("PowerEvent_Extension_Debug","SmtpInstanceID: " . $this->ReadPropertyInteger("SmtpInstanceID"));
			IPS_LogMessage("PowerEvent_Extension_Debug","WebFrontInstanceID: " . $this->ReadPropertyInteger("WebFrontInstanceID"));
			
			if (GetValueFloat($this->ReadPropertyInteger("CurrentVar")) < $this->ReadPropertyInteger("CurrentBoundary")) {
				// CurrentVar im Bereich Zustand1
				if (GetValueInteger(IPS_GetObjectIDByName ("PowerEvent_Extension_LastState", $this->InstanceID )) != 1) {
					
					SetValue(IPS_GetObjectIDByName ("PowerEvent_Extension_LastState", $this->InstanceID ), 1);
					SetValue(IPS_GetObjectIDByName ("PowerEvent_Extension_LastStateChange", $this->InstanceID ), time());
					
					// Notify auslösen
					
					if ($this->ReadPropertyInteger("StandstillTimer") == 0) {
						// keine Verzögerung bis zur Benachrichtigung
						$this->Notify();
												
					}	else {
						// Timer auf entsprechende Zeit setzen und aktivieren
						$timerId = IPS_GetObjectIDByName("PowerEvent_Extension_NotifyTimer", $this->InstanceID );
						
						$spaeter = getdate(time() + $this->ReadPropertyInteger("StandstillTimer")*60);
						
						IPS_SetEventCyclicTimeFrom($timerId, $spaeter["hours"], $spaeter["minutes"], $spaeter["seconds"]);
												
						IPS_SetEventLimit($timerId, 1);
						IPS_SetEventActive($timerId, true);
						
					}
					
				}				
			} else {
				// CurrentVar im Bereich Zustand2
				if (GetValueInteger(IPS_GetObjectIDByName ("PowerEvent_Extension_LastState", $this->InstanceID )) != 2) {
					
					SetValue(IPS_GetObjectIDByName ("PowerEvent_Extension_LastState", $this->InstanceID ), 2);
					SetValue(IPS_GetObjectIDByName ("PowerEvent_Extension_LastStateChange", $this->InstanceID ), time());
					// Notify auslösen
					
					if ($this->ReadPropertyInteger("StandstillTimer") == 0) {
						// keine Verzögerung bis zur Benachrichtigung
						$this->Notify();
												
					}	else {
						// Timer auf entsprechende Zeit setzen und aktivieren
						$timerId = IPS_GetObjectIDByName("PowerEvent_Extension_NotifyTimer", $this->InstanceID );
						
						$spaeter = getdate(time() + $this->ReadPropertyInteger("StandstillTimer")*60);
						
						IPS_SetEventCyclicTimeFrom($timerId, $spaeter["hours"], $spaeter["minutes"], $spaeter["seconds"]);
												
						IPS_SetEventLimit($timerId, 1);
						IPS_SetEventActive($timerId, true);
						
					}
				}
			}
        }


		
		public function Notify() {
            // Timer deaktivieren
			$this->SetTimerInterval("PowerEvent_Extension_NotifyTimer",0);
			
			// Selbsterstellter Code
			IPS_LogMessage("PowerEvent_Extension_Debug","Notify me: " . GetValueInteger(IPS_GetObjectIDByName ("PowerEvent_Extension_LastState", $this->InstanceID )));
        }
		
    }
?>
