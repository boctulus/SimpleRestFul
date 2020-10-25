@echo off

cd C:\xampp\htdocs\simplerest\app\locale\en_US\LC_MESSAGES
msgfmt validator.po -o validator.mo

cd C:\xampp\htdocs\simplerest\app\locale\es_AR\LC_MESSAGES
msgfmt validator.po -o validator.mo

cd C:\xampp\htdocs\simplerest\

