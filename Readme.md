Install:
-------------

- create database named nuclide
- import db dump from database folder
- edit /classes/SPDO.php -> change access to your database (starting Line 20)
- Go to /display.php


how to use:
-----------

- click on one of the isotopes to edit them.
- If you find a decay that has not a field in the database yet: tell me (Moritz Mair)
- write down the percentage of the decay the isotop has
- if the percentage is not known write "111" instead (it will be automatically prozessed)
- when you're done click save, the next isotop will be shown.


generating svg
--------------
- open svg_gen.php to generate and download a svg file of the table of nuclide
- open svg_gen.php?print=1 to lay the table flat so you can print it more efficient
- open svg_gen.php?split=1 to get a split the table


