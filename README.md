
# Gerador de excel

Um projeto utilizado para gerar Excel a partir dos dados enviados do banco de dados.


## Instalação

Clone repo

```bash
  git clone https://github.com/MauricioCruzPereira/GeneratorExcel.git
  ou
  git clone git@github.com:MauricioCruzPereira/GeneratorExcel.git
```
    
## Uso/Exemplos

Para começar a utilizar basta substiuir as informações para adaptar para o seu banco.

(Por enquanto atualmente só é possivel com dados de banco de dados.)

```php
//Inicia conexão com o banco de dados
Database::config('host','banco','senha','user');

//Escolha a tabela que será analisada
$conn = new Database('tabela');
//os campos a serem pegos
$fields = 'campos desejado';
//Recebe o resultado da query
$result = $conn->select("DIGITE A WHERE",'','',$fields);

```


## Stack utilizada

**Back-end:** Php


## Autores

- [@MauricioCruzPereira](https://github.com/MauricioCruzPereira)