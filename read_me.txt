//--------------------------------------------------------------------------- 
// Author : Anatol Leskovski (alterra@sokol.ru)
// Author Website : http://www.photoman.ru
// MODIFED by ArTeMkA (nedam@mail.ru)
// Licence Type : GPL
// ------------------------------------------------------------------------- 
Eng
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
CSV import working with text file in this format:
Item Name;Maker;Item_per_Box;PriceUS;PriceRU;Comment
...
e.g.
Processor Celleron 1700;Intel;Small Box;55.35;12312.0;Nice Processor
Catalogue name ../modules/pricelist/cache, must be attributes 777 (rw).
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Рус
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SVS импорт работает с текстовым файлом содержащим строки следующего формата:
Название товара;Производитель;Упаковка;ЦенаUS;ЦенаRU;Комментарий
Например:
Processor Celleron 1700;Intel;Маленькая коробка;55.35;12312.0;Хороший процессор
Деректория с именем ../modules/pricelist/cache должна иметь атрибуты 777 (чтение/запись).
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++