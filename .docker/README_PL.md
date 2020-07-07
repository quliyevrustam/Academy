## Bulbulators — środowisko oparte na Dockerze

#### Przed pierwszym uruchomieniem

Stwórz plik `.env` w katalogu `.docker`. Możesz go utworzyć na podstawie pliku `.env.example` z tego samego folderu i uzupełnić brakujące pola.

Dodaj poniższy wpis do pliku `/etc/hosts`:
```
127.0.0.1 bulbulatory.test
```
Aplikacja będzie dostępna pod adresem `http://bulbulatory.test`.

#### Pierwsze uruchomienie
```
./dc.sh init
```
Powyższa komenda pobierze wszystkie niezbędne obrazy dockerowe i uruchomi kontenery. Przeprowadzi także początkową konfigurację aplikacji oraz zaimportuje dane do bazy oraz pliki z mediami. 

#### Zarządzanie aplikacją
* `./dc.sh up` - uruchamia kontenery
* `./dc.sh down` - usuwa kontenery
* `./dc.sh import_db <file.sql>` - importuje wskazany plik SQL 
* `./dc.sh import_media <media.zip>` - importuje wskazany plik z mediami

#### Przydatne linki
* [Bulbulatory - Strona Główna](http://bulbulatory.test)
* [Bulbulatory - Panel Administracyjny](http://bulbulatory.test/admin)
* [MailHog](127.0.0.1:8025) - narzędzie do testowania emaili, wszystkie maile wysłane z aplikacji będą widoczne w tym panelu

#### Rozwiązywanie problemów

##### 1. Błąd podczas uruchamiania indexera Catalog Search

Jeśli podczas uruchamiania indexera Catalog Search pojawia się poniższy błąd:

```
{"error":{"root_cause":[{"type":"cluster_block_exception","reason":"blocked by: [FORBIDDEN/12/index read-only / allow delete (api)];"}],"type":"cluster_block_exception","reason":"blocked by: [FORBIDDEN/12/index read-only / allow delete (api)];"},"status":403}
```

Oznacza to, że na dysku jest mało miejsca i Elasticsearch automatycznie przechodzi w tryb tylko do odczytu. Oczywiste rozwiązanie to zwolnienie trochę przestrzeni dyskowej. Inna opcja to zresetowanie ustawień read-only poprzez wysłanie odpowiedniego requestu do Elasticsearch:

```
curl -XPUT -H "Content-Type: application/json" http://localhost:9200/_all/_settings -d '{"index.blocks.read_only_allow_delete": null}'
``` 
