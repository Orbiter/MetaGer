path="$1"
# Klone das Repo neu, falls es noch nicht existiert
if [ ! -d ~/MetaGer ]
then 
	cd ~/
	git clone "$path" 
	cd ~/MetaGer
	composer update 
	chmod -R 777 storage/ bootstrap/cache
fi
# Falls notwendig Laravel-Framework initialisieren:

if [ ! -f ~/MetaGer/.env ]
then 
	cd ~/MetaGer
	cp .env.example .env
fi
if [ $(grep "SomeRandomString" .env) ]
then 
	cd ~/MetaGer
	php artisan key:generate
fi
    # Falls notwendig Homestead Server intialisieren:
  #  - cd ~/
   # - vagrant box add --provider virtualbox laravel/homestead