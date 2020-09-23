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
````yaml
security:
    providers:
        our_db_provider:
            entity:
                class: App\Entity\User
                property: username
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            pattern: ^/
            provider: our_db_provider
            anonymous: ~
            form_login:
                login_path: login
                check_path: login
            logout:
                path: logout
                target: home
encoders:
        App\Entity\User:
            algorithm: bcrypt
    access_control:
        # - { path: ^/admin, roles: ROLE_ADMIN }
        - { path: ^/profile, roles: ROLE_USER }

````
##### Tp 7 Relations entre entités
##### Tp 8 Utilisateurs
```php bin/console make:entity User```  
```composer req security```

  
##### Tp Libre API REST
config yaml
````yaml
security:
    providers:
        our_db_provider:
            entity:
                class: App\Entity\User
                property: username
    firewalls:
         main:
            pattern: ^/
            provider: our_db_provider
            anonymous: ~

            http_basic: true
    encoders:
        Symfony\Component\Securit\Core\User\User:
            algorithm: plaintext
````
controller
````php
/**
 * @Route("/idea/insert", name="api_idea_insert",methods={"PUT"})
 */
public function insert(Request $request, EntityManagerInterface $em){
    if($this->isGranted('ROLE_USER')){
        $params = $request->query->all();
        // créer lobj et inserer en base
        return $this->json(['user'=>$this->getUser()->getUsername(), 'data' => $params]);
    }else {
        return $this->json(["error"=>"forbidden, need to authenticate"]);
    }
}
````