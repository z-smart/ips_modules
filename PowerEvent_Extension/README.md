### Z-Smart IP-Symcon Powerevent_Extension
---

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang) 
2. [Systemanforderungen](#2-systemanforderungen)
3. [Installation](#3-installation)
4. [Changelog](#4-changelog) 

## 1. Funktionsumfang
Dieses Modul dient der Überwachung eines Homematic-Schaltaktors mit Leistungsmessung um abhängig einer Leistungsgrenze einen Zustand zu ermitteln.
Zum Beispiel kann auf diese Weise festgelegt werden, ob eine Waschmaschine läuft oder der Waschvorgang beendet ist. Abhängig vom Zustand kann dann eine 
Nachricht verschickt (Push oder Email) oder ein Skript ausgeführt werden. Außerdem gibt es einen "Zeitfilter" um ein flapping des Zustands zu vermeiden.

## 2. Systemanforderungen
- IP-Symcon ab Version 4.x
- Homematic Aktor mit Leistungsmessung HM-ES-PMsw1-PI oder ähnlich

## 3. Installation
Über die Kern-Instanz "Module Control" folgende URL hinzufügen:
`https://github.com/z-smart/ips_modules.git`
Die Module können dann wie IP-Symcon eigene Module hinzugefügt werden.


## 4. Changelog
Version 1.0:
  - erste stable Version mit korrekter Funktion
Version 1.1:
  - Fehler bei der Variablen Deklaration behoben
  
