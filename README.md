# Lab 5 – Authentication (UUID, Brand i JWT)

Com a tal fer els exercicis no compta per a nota, però si els pengeu al Moodle podré tenir-ho en compte.

Vull que no els feu amb IA per a que entengueu el que esteu fent, si teniu algun dubte o alguna cosa que no sabeu fer, feu un mail a david.domenech@urv.cat

---

## Com entregar-ho

### Opció A

1- Feu un fork d'aquest repositori al vostre compte de Github.

2- Treballeu els exercicis al vostre ordinador i feu commits a la branca `main` del vostre fork.

3- Pengeu el link del vostre fork al Moodle per a que pugui revisar-ho.

### Opció B

1- Feu un fork d'aquest repositori al vostre compte de Github.

2- Feu checkout a una branca amb el vostre nom (ex: `git checkout -b david_domenech`).

3- Un cop tingueu els exercicis fets, feu un commit i un push a la vostra branca.

4- Feu un pull request desde la vostra branca cap aquest repositori.

5- En el pull request, afegiu una descripció del que heu fet i com ho heu fet.

6- Pengeu el link del pull request al Moodle per a que pugui revisar-ho.

## Que fer si no em funciona

Fes un mail a david.domenech@urv.cat explicant el problema que tens, si és possible amb captures de pantalla i logs d'error. Intentaré ajudar-te a resoldre-ho.

Si no ho pots entregar cap problema, envia un mail i ho comptaré igualment, però intenta entregar-ho al Github perquè així és més fàcil per a mi revisar el codi i veure que has fet.

---

## Com començar

1. Feu una carpeta lab5 al vostre ordinador i entreu-hi:

```bash
mkdir lab5
cd lab5
```

2. Cloneu el repositori de la llibreria dins de la carpeta `lab5`:

```bash
git clone https://github.com/Sistemes-de-comerc-electronic/Codi-Llibreria.git
```

3. Cloneu aquest repositori al vostre ordinador (dins de `lab5` també):

```bash
git clone https://github.com/Sistemes-de-comerc-electronic/Lab5.git .
```

4. Si ho heu fet bé haureu de tenir aquesta estructura:

```
lab5/
├── Codi-Llibreria/
├── Lab5/
```

5. Entreu a la carpeta `Lab5` i seguiu les instruccions del README per configurar el projecte Symfony:

```bash
cd Lab5
composer install
````

6. Configureu el fitxer `.env` amb les vostres dades de connexió a la base de dades (usant `lab_bd`). Podeu copiar-ho de labs anteriors.

7. Aixequeu el servidor de desenvolupament:

```bash
symfony server:start
```
---

## UUID

Agafarem el codi back-end del laboratori anterior.

### Pas 1 – Instal·lar Symfony UID

```bash
composer require symfony/uid
```

### Pas 2 – Entitat Brand

Creeu `src/Entity/Brand.php`:

```php
<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Brand
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function __construct(?Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
```

### Pas 3 – Crear la taula a BD

```sql
CREATE TABLE brand (
    id   BINARY(16)   NOT NULL,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
```

### Pas 4 – Endpoint per afegir una Brand

```php
#[Route('/cars/brand/add', name: 'add_brand', methods: ['POST'])]
public function addBrand(): JsonResponse
{
    $brand = new \App\Entity\Brand();
    $brand->setName('Test Brand');

    $this->brandRepository->save($brand, true);

    return new JsonResponse([
        'message'  => 'Brand added successfully',
        'brand_id' => $brand->getId()->toRfc4122(),
    ], 200);
}
```

Comproveu que s'afegeix correctament a la taula `brand` de BD.

---

## Autenticació

En aquesta part no hi ha un exercici guiat sinó que se us demana que implementeu vosaltres mateixos l'autenticació. Podeu fer servir la documentació oficial de Symfony:

### Formulari de login

Documentació: https://symfony.com/doc/current/security.html#form-login

### HTTP Basic (per a l'API)

Documentació: https://symfony.com/doc/current/security.html#http-basic

### Mostrar contingut per rol d'usuari

Documentació: https://symfony.com/doc/current/security.html#fetching-the-user-object

---

## Exercicis

1. Feu un **formulari per crear cotxes** que permeti introduir el nom, model, any i preu.

2. Feu una **pàgina per cotxe** (`/cars/{id}`) que mostri tota la informació d'aquest.

3. **Compteu les visites** de cada cotxe: cada cop que es carregui la pàgina d'un cotxe, incrementeu un comptador a Redis.
   - Podeu guardar-ho a Redis i cada X temps fer un command que ho passi a BD.

4. Feu un **command** que cada dia a les 8 del matí us enviï un report dels cotxes més visitats per correu.

5. Intenteu fer la **lògica tota dins dels UseCase i Serveis**, deixant els controladors el més fins possible.
