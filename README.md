# bucket-list
## Symfony 4 Eni Project

##### Tp 1 Installation de Symfony  
* installation de wamp server + php 7.1 +
* installation Cmder.exe
* installation composer
* installation phpStorm

```composer create-project symfony/skeleton "bucket-list" 4.4.*```  
```composer req annotations```  
```composer req requirements-checker apache-pack make```  
```composer req var-dumper```  
 

#####(pas de tp 2)
```composer req debug```  
```composer req make```  

##### Tp 3 vue et  Twig
```composer req twig```  
```composer req asset``` 
##### Tp 4 Routes et ctrlrs
```php bin/console make:controller``` 

##### Tp 5 Données et Doctrine
```composer req doctrine```  
```php bin/console make:entity User```  
```php bin/console doctrine:database:create```  
```php bin/console doctrine:schema:update```  
```php bin/console doctrine:schema:validate```  
```php bin/console doctrine:schema:update --force```  


```php bin/console debug:router```  
```php bin/console cache:clear```  
##### Tp 6 Formulaire
```composer req form```  
```php bin/console make:form```  
```composer req security-csrf```  
```composer req validator```    

##### Tp 7 Relations entre entités
##### Tp 8 Utilisateurs
```php bin/console make:entity User```  
```composer req security```  
