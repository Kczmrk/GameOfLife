# GameOfLife - REST API

## Odpalenie aplikacji:
1. git clone
2. composer install
3. php bin/console server:start

## Działanie gry:
1. 127.0.0.0:8000/new/[number] --- tworzy nową grę z maksymalna liczbą nasion(seeds) = [number] --- zwraca wynik nowej gry w formacie JSON
2. 127.0.0.0:8000/tick --- zwraca kolejny krok(tick) gry

Zmiana wymiarów planszy w /src/GameOfLife.php.
