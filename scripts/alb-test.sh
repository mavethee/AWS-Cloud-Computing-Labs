#!/bin/bash

# --- KONFIGURACJA ---
# Wklej tutaj adres DNS swojego Load Balancera (z zakładki Outputs)
URL="http://marcinmitura-alb-1182031536.us-east-1.elb.amazonaws.com/"
ITERATIONS=20 # Ile razy odpytać serwer

# --- KOLORY ---
RED='\033[0;31m'
GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color (Reset)

echo "Rozpoczynam test Load Balancera: $URL"
echo "---------------------------------------------------"

for ((i=1; i<=ITERATIONS; i++)); do
    # Pobieramy stronę (curl -s = silent mode, bez paska postępu)
    CONTENT=$(curl -s --max-time 2 "$URL")

    # Sprawdzamy, czy curl zwrócił pusty wynik (błąd połączenia)
    if [ -z "$CONTENT" ]; then
        printf "Próba %02d: ${YELLOW}Błąd połączenia / Timeout${NC}\n" $i
    else
        # Analiza treści strony (szukamy słów kluczowych z Twojego HTML)
        if echo "$CONTENT" | grep -q "Stress Test Aktywny"; then
            printf "Próba %02d: ${RED}CZERWONA (Atak - Parzyste IP)${NC}\n" $i
        elif echo "$CONTENT" | grep -q "Status: Inicjalizacja"; then
            printf "Próba %02d: ${CYAN}NIEBIESKA (Czeka - Start systemu)${NC}\n" $i
        elif echo "$CONTENT" | grep -q "System Zdrowy"; then
            printf "Próba %02d: ${GREEN}ZIELONA (Spokój - Nieparzyste IP)${NC}\n" $i
        else
            # Jeśli strona się załadowała, ale treść jest inna (np. domyślna strona IIS)
            printf "Próba %02d: ${YELLOW}Inna odpowiedź (IIS Default?)${NC}\n" $i
        fi
    fi

    # Krótka pauza (pół sekundy), żeby nie zalać terminala
    sleep 0.5
done