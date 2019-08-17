<?
    // Klassendefinition
    class EnergyVisualizer extends IPSModule {

        // Der Konstruktor des Moduls
        // Überschreibt den Standard Kontruktor von IPS
        public function __construct($InstanceID) {
            // Diese Zeile nicht löschen
            parent::__construct($InstanceID);

            // Selbsterstellter Code

            // bisher nicht nötig
        }

        // Überschreibt die interne IPS_Create($id) Funktion
        public function Create() {
            // Diese Zeile nicht löschen.
            parent::Create();

            // benötigte Struktur im IPS-Baum anlegen
            if (@IPS_GetCategoryIDByName ("Categories", $this->InstanceID ) === false) {
      				$eid = IPS_CreateCategory();                  										// Hauptkategorie anlegen
      				IPS_SetParent($eid, $this->InstanceID );         									// Var zuordnen
      				IPS_SetName($eid, "Categories");

              // Subkategorien anlegen
              $subeid = IPS_CreateCategory();
      				IPS_SetParent($subeid, $eid );
      				IPS_SetName($eid, "Licht");

              // Subkategorien anlegen
              $subeid = IPS_CreateCategory();
              IPS_SetParent($subeid, $eid );
              IPS_SetName($eid, "Heizung");

              // Subkategorien anlegen
              $subeid = IPS_CreateCategory();
      				IPS_SetParent($subeid, $eid );
      				IPS_SetName($eid, "Sonstiges");

      			}

            if (@IPS_GetCategoryIDByName ("Complete", $this->InstanceID ) === false) {
      				$eid = IPS_CreateCategory();
      				IPS_SetParent($eid, $this->InstanceID );
      				IPS_SetName($eid, "Complete");
      			}

            // Var anlegen für AutoCalcComplete
            $this->RegisterPropertyBoolean("AutoCalcComplete", 0);

            // Var anlegen für ArchiveHandlerID
            $this->RegisterPropertyInteger("ArchiveHandlerID", 0);


        }

        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();

            $this->setStatus(102);

            $this->setStatus(CheckVars());

        }

        // Diese Funktion prüft ob alle Vars korrkt eingestellt sind.
        public function CheckVars() {
            $error = 102;
            // Alle Kategorien durchgehen und Vars Prüfen
            $catID = IPS_GetCategoryIDByName ("Categories", $this->InstanceID );

            $subCats = IPS_GetChildrenIDs ($catID);

            foreach ($subCats as $key => $value) {
                if (IPS_CategoryExists($value)) {
                  // es handelt sich um eine Kategorie, also nach Links suchen

                    $subLinks = IPS_GetChildrenIDs ($value);

                    foreach ($subLinks as $key => $object) {
                      if (IPS_LinkExists($object)) {
                        foreach (IPS_GetLink($object) as $LinkID => $targetID) {

                          $varArray = IPS_GetVariable($targetID);

                          if (!($varArray['VariableType'] == 2 && AC_GetAggregationType($this->ReadPropertyInteger("ArchiveHandlerID"), $targetID) == 1)) $error = 203;

                        }
                      } else $error = 201;
                    }
                } else $error = 201;
            }

            // Wenn der Gesamtverbrauch nicht berechnet wird, dann prüfen ob gültige LInks zum auslesen vorhanden sind
            if (!($this->ReadPropertyBoolean("AutoCalcComplete"))) {

              $completeID = IPS_GetCategoryIDByName ("Complete", $this->InstanceID );

              $subLinks = IPS_GetChildrenIDs ($completeID);

                      foreach ($subLinks as $key => $object) {
                        if (IPS_LinkExists($object)) {
                          foreach (IPS_GetLink($object) as $LinkID => $targetID) {

                            $varArray = IPS_GetVariable($targetID);

                            if !($varArray['VariableType'] == 2 && AC_GetAggregationType($this->ReadPropertyInteger("ArchiveHandlerID"), $targetID) == 1) $error = 202;

                          }
                        } else $error = 202;
                      }

            }



        return($error);
        }





    }
?>
