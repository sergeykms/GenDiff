Программа сравнивает два конфигурационных файла. 
Принимает через командную строку аргументы — формат, в котором будет отображаться отчет о сравнении и
и пути до сравниваемых файлов. Сранение возможно для файлов в формате .json, .yaml, .yml

### 1. Вывод отчета в формате Stylish

gendiff --format stylish test/file1.json test/file2.json 

результат выполнения команд 

gendiff --format stylish test/file1.json test/file2.json или 

gendiff test/file1.json test/file2.json

Приведет к выводу отчета в формате Stylish (пропуск аргумента формат будет расцениваться как формат Stylish)

Демо https://asciinema.org/a/1UckA7LMMFsr2gv2vPBJLfILd

### 2. Вывод отчета в формате Plain

gendiff --format plain test/file1.json test/file2.json

Демо https://asciinema.org/a/1UckA7LMMFsr2gv2vPBJLfILd

### 3. Вывод отчета в формате Plain
gendiff --format json test/file1.json test/file2.json

Демо https://asciinema.org/a/1UckA7LMMFsr2gv2vPBJLfILd

### Установка

Для установки программы склонируйте репозиторий. Перейдите в каталог GenDiff
и выполните команду make install 
```
git clone git@github.com:sergeykms/GenDiff.git
cd GenDiff
make install
```
Демо https://asciinema.org/a/1UckA7LMMFsr2gv2vPBJLfILd

### Hexlet tests and linter status:
[![Actions Status](https://github.com/sergeykms/php-project-48/actions/workflows/main.yml/badge.svg)](https://github.com/sergeykms/php-project-48/actions)


[![Maintainability](https://api.codeclimate.com/v1/badges/799fff2f02dc1eb97dca/maintainability)](https://codeclimate.com/github/sergeykms/GenDiff/maintainability)

<a href="https://codeclimate.com/github/sergeykms/GenDiff/test_coverage"><img src="https://api.codeclimate.com/v1/badges/799fff2f02dc1eb97dca/test_coverage" /></a>

Setup
```
git clone git@github.com:sergeykms/GenDiff.git
cd GenDiff
make install
```
Демо https://asciinema.org/a/1UckA7LMMFsr2gv2vPBJLfILd
