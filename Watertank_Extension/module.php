<?
    // Klassendefinition
    class Watertank_Extension extends IPSModule {
 
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
			
			// Zu überwachende FillingLevelVar
			$this->RegisterPropertyInteger("FillingLevelVar", 0);
			$this->RegisterPropertyInteger("WatertankVolume", 0);
			$this->RegisterPropertyInteger("MinChangeValue", 0);
			
			// Zähler Vars und HTML Var anlegen
			$eid = IPS_CreateVariable(1);                  										// Neue Var für den letzten Filling_Level
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Last_Level");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var für das aktuelle Volumen
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Current_Volume");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var für das aktuelle Volumen in %
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Current_Volume_Percent");
			SetValue($eid, 0);
						
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern des höchsten Level-Werts
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_UpperBoundary");
			SetValue($eid, 60);

			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern des niedrigsten Level-Werts
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_LowerBoundary");
			SetValue($eid, 40);

			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch aktuelles Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Year1");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch letztes Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Year2");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch vorletztes Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Year3");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch aktuelles Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Year1_Liter");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch letztes Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Year2_Liter");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch vorletztes Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Year3_Liter");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch vorletztes Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_Year_Last_Change");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(3);                  										// Neue Var zum speichern des HTML Codes
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_HTML");
			SetValue($eid, 0);
			
			$eid = IPS_CreateVariable(1);                  										// Neue Var zum speichern Wasserverbrauch vorletztes Jahr
			IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
			IPS_SetName($eid, "Watertank_Extension_actual_Year");
			SetValue($eid, 0);
			
        }
 
		public function Destroy() {
			
			parent::Destroy();
		}
 
 
        // überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            
			// Diese Zeile nicht löschen
            parent::ApplyChanges();
			
			// Eingaben prüfen und im ersten Schritt Status auf "102" setzen
			$this->setStatus(102);
			
			if ($this->ReadPropertyInteger("WatertankVolume") < 100 || $this->ReadPropertyInteger("WatertankVolume") > 30000) $this->SetStatus(210);
			
			
			if ($this->ReadPropertyInteger("FillingLevelVar") == 0 ) {
				$this->SetStatus(220);
			} else {
				// Event für FillingLevelVar anlegen und im Falle eines Updates die entsprechende Update-Function auslösen
				
				// alten Event ggf. löschen
				@IPS_DeleteEvent(IPS_GetObjectIDByName ("Watertank_Extension_ChangeEvent", $this->InstanceID ));
				
				// Neuen Event anlgen
				$eid = IPS_CreateEvent(0);                  										//Ausgelöstes Ereignis
				IPS_SetEventTrigger($eid, 1, $this->ReadPropertyInteger("FillingLevelVar") );		//Bei Änderung von Variable
				IPS_SetParent($eid, $this->InstanceID );         									//Ereignis zuordnen
				IPS_SetEventScript($eid, "Watertank_Update(".$this->InstanceID.");");
				IPS_SetName($eid, "Watertank_Extension_ChangeEvent");
				IPS_SetEventActive($eid, true);
			}
			
			
			if ($this->ReadPropertyInteger("MinChangeValue") < 1 || $this->ReadPropertyInteger("MinChangeValue") > 5) $this->SetStatus(230);
			
			
        }
 
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * Watertank_Update($id);
        *
        */
        public function Update() {
			
			$FillLevel = GetValueInteger($this->ReadPropertyInteger("FillingLevelVar"));
			
			// prüfen ob neuer Level größer oder kleiner der bekannten Größen ist und diese ggf. anpassen / kalibrieren
			if ($FillLevel < GetValueInteger(IPS_GetObjectIDByName ("Watertank_Extension_LowerBoundary", $this->InstanceID ))) {
				SetValue(IPS_GetObjectIDByName ("Watertank_Extension_LowerBoundary", $this->InstanceID ), $FillLevel);				
			}
			
			if ($FillLevel > GetValueInteger(IPS_GetObjectIDByName ("Watertank_Extension_UpperBoundary", $this->InstanceID ))) {
				SetValue(IPS_GetObjectIDByName ("Watertank_Extension_UpperBoundary", $this->InstanceID ), $FillLevel);				
			}
		
			
			$RealFillLevel = $FillLevel - GetValueInteger(IPS_GetObjectIDByName("Watertank_Extension_LowerBoundary", $this->InstanceID));
			$MaxSteps =  GetValueInteger(IPS_GetObjectIDByName("Watertank_Extension_UpperBoundary", $this->InstanceID)) - GetValueInteger(IPS_GetObjectIDByName("Watertank_Extension_LowerBoundary", $this->InstanceID));
			
			// aktuelles Volumen kalkulieren
			
			SetValue(IPS_GetObjectIDByName("Watertank_Extension_Current_Volume", $this->InstanceID), (GetValueInteger($this->ReadPropertyInteger("WatertankVolume")) / $MaxSteps * $RealFillLevel)); 
			
			SetValue(IPS_GetObjectIDByName("Watertank_Extension_Current_Volume_Percent", $this->InstanceID), 100 / $MaxSteps * $RealFillLevel);


			// Jahreswerte ermitteln

				// prüfen ob ein Jahreswechsel vorliegt und ggf. alle Werte um ein Jahr nach hinten shiften
				if (GetValueInteger(IPS_GetObjectIDByName("Watertank_Extension_actual_Year", $this->InstanceID)) != intval(date("Y")) ) {
					
					SetValue(IPS_GetObjectIDByName("Watertank_Extension_Year3", $this->InstanceID), GetValueInteger(IPS_GetObjectIDByName("Watertank_Extension_Year2", $this->InstanceID)));
					SetValue(IPS_GetObjectIDByName("Watertank_Extension_Year2", $this->InstanceID), GetValueInteger(IPS_GetObjectIDByName("Watertank_Extension_Year1", $this->InstanceID)));
					SetValue(IPS_GetObjectIDByName("Watertank_Extension_Year1", $this->InstanceID),0);
					
					SetValue(IPS_GetObjectIDByName("Watertank_Extension_actual_Year", $this->InstanceID), intval(date("Y")));
					
				}
			
				// aktuellen Jahresverbrauch kalkulieren, bzw. aufaddieren (Hier den MinChangeValue berücksichtigen)
				
				if (($_IPS['OLDVALUE']-($this-ReadPropertyInteger("MinChangeValue")-1) > $_IPS['VALUE'] )) {
					SetValue(IPS_GetObjectIDByName ( "Watertank_Extension_Year1", $this->InstanceID ),
						GetValue(IPS_GetObjectIDByName( "Watertank_Extension_Year1", $this->InstanceID )) +	( $_IPS['OLDVALUE'] - $_IPS['VALUE'] ) );
				}
				
				// Liter-Angaben für die 3 Jahre berechnen
				
				SetValue(IPS_GetObjectIDByName("Watertank_Extension_Year1_Liter", $this->InstanceID),  
					GetValue(IPS_GetObjectIDByName("Watertank_Extension_Year1", $this->InstanceID)) * ( GetValueInteger($this->ReadPropertyInteger("WatertankVolume")) / $MaxSteps )  );
				
				SetValue(IPS_GetObjectIDByName("Watertank_Extension_Year2_Liter", $this->InstanceID),  
					GetValue(IPS_GetObjectIDByName("Watertank_Extension_Year2", $this->InstanceID)) * ( GetValueInteger($this->ReadPropertyInteger("WatertankVolume")) / $MaxSteps )  );
				
				SetValue(IPS_GetObjectIDByName("Watertank_Extension_Year3_Liter", $this->InstanceID),  
					GetValue(IPS_GetObjectIDByName("Watertank_Extension_Year3", $this->InstanceID)) * ( GetValueInteger($this->ReadPropertyInteger("WatertankVolume")) / $MaxSteps )  );
				
				// HTML String mit aktuellen Daten erzeugen
				$HTML_Code= "Test";
				
				SetValue(IP_GetObjectIDByName("Watertank_Extension_HTML", $this->InstanceID),$HTML_Code);
				

		}


		
		
  
  }
?>
