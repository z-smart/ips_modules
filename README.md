### Z-Smart IP-Symcon Bibliothek
---

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang) 
2. [Systemanforderungen](#2-systemanforderungen)
3. [Installation](#3-installation)
4. [Changelog](#4-changelog) 

## 1. Funktionsumfang
Diese Bibliothek beinhaltet IP-Symcon Module (ab Version 5.0) zur Ergänzung diverser Funktionen. Einige Module sind komplett eigenständig, 
andere sind als Ergänzung zu bestehenden Homematic Instanzen gedacht. Die Ergänzuungen/Erweiterungen tragen alle den Namenszusatz "Extension".

## 2. Systemanforderungen
- IP-Symcon ab Version 5.x (frühere Versionen können funktionieren, wurden aber nicht getestet)
- je nach Modul die entsprechenden Homematic-Aktoren/Gateways

## 3. Installation
Über die Kern-Instanz "Module Control" folgende URL hinzufügen:
`https://github.com/z-smart/ips_modules.git`
Die Module können dann wie IP-Symcon eigene Module hinzugefügt werden.


## 4. Changelog
Version 1.0:
  - Anpassung Bibliothek auf die erste offizielle Version und Anpassung der Module auf aktuelle IPS Version
  
Version 0.1:
  - Erster struktureller Aufbau des Repos. Bisher praktisch ohne Funktion
