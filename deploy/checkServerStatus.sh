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
if [ ! -d ~/Homestead ]
then
	cd ~/
	# Wir brauchen unseren öffentlichen ssh Schlüssel:
	if [ ! -d .ssh ]
	then
		mkdir .ssh
		chmod 700 .ssh
	fi
	if [ ! -f .ssh/id_rsa.pub ]
	then
		cd .ssh
		ssh-keygen -t rsa -N "" -f id_rsa
		cd ~/
	fi
	vagrant box add laravel/homestead
	git clone https://github.com/laravel/homestead.git Homestead
	cd Homestead/
	bash init.sh
	mv -f "$path/deploy/Homestead.yaml" .homestead/
	vagrant up
	echo "Your server is now running under http://localhost:8000"
fi
