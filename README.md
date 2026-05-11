# AWS Cloud Computing Labs

![AWS](https://img.shields.io/badge/AWS-FF9900?style=for-the-badge&logo=amazonaws&logoColor=white) ![CloudFormation](https://img.shields.io/badge/CloudFormation-VP?style=for-the-badge&logo=amazon-aws&logoColor=white) ![WordPress](https://img.shields.io/badge/WordPress-21759B?style=for-the-badge&logo=wordpress&logoColor=white) ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

Projekt ten jest objęty licencją [MIT](./LICENSE.md).

## Struktura projektu

```sh
~/AWS-Cloud-Computing-Labs
├── infrastructure/                 # Katalog definicji zasobów chmurowych
│   └── AWS-WP-WooCommerce.yaml     # Główny szablon CloudFormation (ALB, ASG, RDS, EFS)
├── src/                            # Folder źródłowy danych aplikacji i kopii zapasowych
│   ├── assets/                     # Zasoby statyczne wykorzystywane przez stronę
│   │   ├── favicon-techfix.png     # Ikona serwisu wyświetlana w przeglądarce
│   │   ├── TechFix_Products.csv    # Baza danych produktów do importu w WooCommerce
│   │   └── techfix-*.png           # Zdjęcia produktów i elementy graficzne interfejsu
│   └── backups/                    # Katalog przechowujący obrazy witryny
│       └── AWS-WP-TechFix.wpress   # Kopia zapasowa WordPress do odtworzenia serwisu
├── images/                         # Dokumentacja wizualna projektu (zrzuty ekranu)
│   ├── LoginWindow.png             # Prezentacja widoku logowania administratora
│   ├── MainShopPage.png            # Prezentacja głównej strony sklepu TechFix
│   ├── OrderConfirmation.png       # Prezentacja widoku podsumowania zamówienia
│   └── ProductInCart.png           # Prezentacja widoku produktów w koszyku
├── LICENSE.md                      # Plik określający zasady licencji MIT
└── README.md                       # Dokumentacja, instrukcja wdrożenia i dane logowania
```

## Kluczowe cechy

- Wysoka dostępność: ALB + ASG + RDS Multi-AZ
- Trwałość danych: EFS dla `wp-content` oraz backupy w `src/backups`
- Bootstrapping: UserData konfiguruje instancje przy starcie
- Skalowanie: ASG z politykami opartymi na metrykach

## Szybki start (Deployment)

1. Otwórz konsolę AWS → CloudFormation.
2. Create stack → Upload a template file → wybierz szablon z `infrastructure/`.
3. Uzupełnij parametry (nazwa bazy, użytkownik, hasło) i uruchom stack.
4. Po zakończeniu sprawdź *Outputs* — znajdziesz tam URL aplikacji i inne wartości.

### Import projektu WordPress

> [!NOTE]
> Po zaimportowaniu projektu, dane logowania do panelu WordPress to `mmitura` / `cuhwy6-sohXib-qacniq`.
>

1. Zaloguj się do panelu WordPress (URL z `Outputs`).
2. Zainstaluj wtyczkę **All-in-One WP Migration**.
3. Zaimportuj plik `.wpress` z `src/backups/`.

## Galeria

| Ekran logowania | Strona produktu | Produkt dodany do koszyka | Potwierdzenie zamówienia |
|:---------------:|:---------------:|:-----:|:----------------------:|
| ![Login](images/LoginWindow.png) | ![Product](images/MainShopPage.png) | ![Cart](images/ProductInCart.png) | ![Order](images/OrderConfirmation.png) |

---

Stworzono z myślą o nauce i rozwoju 😄

**Autor:** Marcin Mitura

**Data:** `2026-05-04`