# AWS Cloud Computing Labs - Trivy Audit dla WordPress + WooCommerce

![AWS](https://img.shields.io/badge/AWS-FF9900?style=for-the-badge&logo=amazonaws&logoColor=white) ![CloudFormation](https://img.shields.io/badge/CloudFormation-VP?style=for-the-badge&logo=amazon-aws&logoColor=white) ![WordPress](https://img.shields.io/badge/WordPress-21759B?style=for-the-badge&logo=wordpress&logoColor=white) ![Trivy](https://img.shields.io/badge/Trivy-Security-blue?style=for-the-badge)

Projekt strony serwisu komputerowego **TechFix** wdrożony w architekturze chmurowej AWS, wzbogacony o pełną analizę bezpieczeństwa szablonu CloudFormation przeprowadzoną przy użyciu narzędzia Trivy.

## Struktura projektu

```sh
~/AWS-Cloud-Computing-Labs
├── images/                         # Zrzuty ekranu prezentujące działający sklep TechFix
│   ├── LoginWindow.png             # Widok panelu logowania administratora WordPress
│   ├── MainShopPage.png            # Strona główna serwisu WooCommerce
│   ├── OrderConfirmation.png       # Widok pomyślnego złożenia zamówienia
│   └── ProductInCart.png           # Widok produktów w koszyku użytkownika
├── infrastructure/                 # Konfiguracja Infrastruktury jako Kod (IaC)
│   ├── AWS-WP-WooCommerce.yaml     # Główny szablon audytowanego systemu TechFix
│   └── samples/                    # Przykładowe pliki z celowo wprowadzonymi błędami (do nauki)
│       ├── p1.yaml                 # Podatność: publiczny S3 i brak szyfrowania RDS
│       ├── p2.yaml                 # Podatność: otwarty port SSH (0.0.0.0/0) i brak szyfrowania EBS
│       ├── p3.yaml                 # Podatność: twardo zapisane hasła w konfiguracji bazy danych
│       └── p4.yaml                 # Podatność: brak VPC Flow Logs i błędy w politykach IAM
├── LICENSE.md                      # Licencja projektu (MIT)
├── README.md                       # Główny plik dokumentacji z instrukcją audytu i wdrożenia
├── reports/                        # Wyniki audytu bezpieczeństwa wygenerowane w ramach labów
│   ├── raport_p1..p4.html/pdf      # Raporty techniczne dla plików przykładowych
│   ├── report_WooCommerce.html     # Szczegółowy raport HTML dla infrastruktury TechFix
│   └── report_WooCommerce.pdf      # Finalny raport audytu w formacie PDF (do wysłania)
└── src/                            # Dane źródłowe i zasoby aplikacji WordPress
    ├── assets/                     # Grafiki produktów, favicon oraz baza produktów (CSV)
    └── backups/                    # Kopie zapasowe
        └── AWS-WP-TechFix.wpress.zip # Skompresowany obraz witryny gotowy do importu w WordPressie
```

## Kluczowe cechy

* **Wysoka dostępność:** Architektura ALB + ASG + RDS Multi-AZ.
* **Trwałość danych:** System EFS dla `wp-content` oraz zautomatyzowane backupy.
* **Audyt Bezpieczeństwa:** Pełne skanowanie IaC pod kątem podatności i błędów konfiguracji.

## Audyt Bezpieczeństwa (Trivy Audit)

W ramach laboratorium przeprowadzono analizę bezpieczeństwa szablonu `AWS-WP-WooCommerce.yaml`.

### 1. Przygotowanie środowiska

Instalacja skanera Trivy (wersja 0.70.0) na systemie macOS przy użyciu Homebrew :

```bash
brew install trivy
trivy --version
```

### 2. Skanowanie szablonu

Wykonanie skanowania w celu identyfikacji błędów konfiguracji (Misconfigurations):

```bash
trivy config infrastructure/AWS-WP-WooCommerce.yaml
```

### 3. Generowanie raportu w formacie HTML i PDF

```bash
# Pobranie szablonu i generowanie raportu HTML
mkdir -p contrib && curl -sSL -o contrib/html.tpl https://raw.githubusercontent.com/aquasecurity/trivy/main/contrib/html.tpl
trivy config --format template --template "@contrib/html.tpl" -o reports/report_WooCommerce.html infrastructure/AWS-WP-WooCommerce.yaml

# Konwersja do formatu PDF z użyciem przeglądarki, gdzie "/Applications/XYZ.app/Contents/MacOS/XYZ" to ścieżka do przeglądarki (np. Google Chrome, Safari) z opcją headless:
"/Applications/XYZ.app/Contents/MacOS/XYZ" --headless --disable-gpu --print-to-pdf=reports/report_WooCommerce.pdf reports/report_WooCommerce.html

```

### 4. Zestawienie kluczowych podatności i błędów konfiguracji

Skanowanie wykryło łącznie **17 podatności** *(1 Critical, 7 High, 4 Medium, 5 Low)*.

| ID (AVD)     | Zasób (Resource)  | Opis podatności                                             | Priorytet    |
| ------------ | ----------------- | ----------------------------------------------------------- | ------------ |
| **AWS-0054** | `ALBListener`     | Użycie nieszyfrowanego protokołu HTTP (port 80).            | **CRITICAL** |
| **AWS-0037** | `EFS::FileSystem` | System plików nie posiada włączonego szyfrowania.           | **HIGH**     |
| **AWS-0080** | `RDS::DBInstance` | Brak włączonego szyfrowania dysku bazy danych.              | **HIGH**     |
| **AWS-0130** | `LaunchTemplate`  | IMDSv2 ustawiony jako opcjonalny (token nie jest wymagany). | **HIGH**     |
| **AWS-0164** | `EC2::Subnet`     | Włączone automatyczne przypisywanie publicznych adresów IP. | **HIGH**     |
| **AWS-0178** | `EC2::VPC`        | Brak włączonych Flow Logs dla sieci VPC.                    | **MEDIUM**   |
| **AWS-0124** | `SecurityGroup`   | Brak opisów reguł wymaganych do celów audytowych.           | **LOW**      |

---

Stworzono z myślą o nauce i rozwoju 😄

**Autor:** Marcin Mitura

**Data:** `2026-05-11`
