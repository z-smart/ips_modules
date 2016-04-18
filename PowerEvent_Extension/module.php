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
			$this->RegisterPropertyInteger("CurrentVar", 0);
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
			$this->RegisterTimer("PowerEvent_UpdateTimer", 0, 'PowerEvent_Update($_IPS[\'TARGET\']);');
        }
 
		public function Destroy() {
					
			$this->UnregisterTimer("PowerEvent_UpdateTimer");
			
			parent::Destroy();
		}
 
 
        // überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();
			
			// Eingaben prüfen
			
			if ($this->ReadPropertyInteger("CurrentBoundary") < 1 || $this->ReadPropertyInteger("CurrentBoundary") > 16000) $this->SetStatus(210);
			
			if ($this->ReadPropertyInteger("StandstillTimer") < 1 || $this->ReadPropertyInteger("StandstillTimer") > 10) $this->SetStatus(211);
			
			if ((($this->ReadPropertyBoolean("PushState1") == true) || ($this->ReadPropertyBoolean("PushState2") == true)) && ($this->ReadPropertyInteger("WebFrontInstanceID") == "")) $this->SetStatus(201);
			if ((($this->ReadPropertyBoolean("EmailState1") == true) || ($this->ReadPropertyBoolean("EmailState2") == true)) && ($this->ReadPropertyInteger("SmtpInstanceID") == "")) $this->SetStatus(202);
			if ((($this->ReadPropertyBoolean("ScriptState1") == true) || ($this->ReadPropertyBoolean("ScriptState2") == true)) && ($this->ReadPropertyInteger("WebFrontInstanceID") == "")) $this->SetStatus(203);
			
			if ($this->ReadPropertyInteger("CurrentVar") > 0 ) $this->SetStatus(220);
			IPS_LogMessage("PowerEvent_Extension_Debug","CurrentVar: " . $this->ReadPropertyInteger("CurrentVar"));
        }
 
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * PowerEvent_MeineErsteEigeneFunktion($id);
        *
        */
        public function Update() {
            // Selbsterstellter Code
        }
		
		public function Notify() {
            // Selbsterstellter Code
        }
		
    }
?>
