# MyVoyage Blog

Ein OOP-basiertes Full-Stack-Webprojekt zur Verwaltung und Veröffentlichung von Reiseberichten.

---

## 🎯 Hauptfeatures

* **Besucher-Modul:** Responsive UI mit performanter AJAX-Navigation für ein flüssiges Nutzererlebnis ohne vollständigen Seiten-Reload.
* **Autoren-Modul (CMS):** Passwortgeschütztes Dashboard zur vollständigen Verwaltung (CRUD) von Themen und Blogbeiträgen.
* **Security & Performance:** Verschlüsselte HTTPS-POST-Datenübermittlung und asynchrone Serveranfragen.

---

## 🛠️ Technologie-Stack

| Schicht | Technologien | Beschreibung |
| :--- | :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript | Responsives Design und visuelle Effekte |
| **Backend** | PHP (OOP) | Serverseitige Logik, objektorientierte Architektur |
| **Datenbank** | MySQL | Relationale Datenhaltung und strukturierte Abfragen |
| **Kommunikation** | AJAX / HTTPS POST | Schneller, asynchroner Datenaustausch und sichere Formularübermittlung |

---

## 💾 Datenhaltung & Datenbanksicherheit (MySQL)

Die persistente Speicherung der Blogeinträge, Kategorien und Benutzerdaten erfolgt über eine **MySQL-Datenbank**. Bei der Implementierung wurde besonderer Wert auf moderne Standards und IT-Sicherheit gelegt:

* **Sichere Datenbankverbindung:** Die Anbindung an den MySQL-Server wird über **PDO (PHP Data Objects)** realisiert, was eine flexible und objektorientierte Datenbankschnittstelle ermöglicht.
* **Schutz vor SQL-Injections:** Alle datenbankrelevanten Operationen (insbesondere beim Login und beim Erstellen von Beiträgen) werden konsequent über **Prepared Statements** (vorbereitete SQL-Abfragen) und *Parameter-Binding* ausgeführt. Böswillige SQL-Injektionen werden dadurch effektiv verhindert.
* **Datenintegrität:** Die Tabellenstruktur nutzt relationale Verknüpfungen (Foreign Keys), um die Konsistenz zwischen Autoren, Themen und den dazugehörigen Blogbeiträgen sauber abzubilden.
* **Sichere Konfiguration:** Sensible Zugangsdaten (Datenbank-Passwörter, Hostnamen) sind strikt vom Quellcode getrennt und werden über eine vom Git-Repository ausgeschlossene Konfigurationsdatei geladen.

  
1. **Repository klonen:**
```bash
   git clone [https://github.com/rekiraly/projekt_blog.git](https://github.com/rekiraly/projekt_blog.git)
