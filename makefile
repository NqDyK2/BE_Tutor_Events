SCRIPT_VERSION=v1.0
SCRIPT_AUTHOR=Theldph15731
tutor_phpcs=vendor/bin/phpcs --standard=ruleset.xml --extensions=php app routes config
tutor_phpcbf=vendor/bin/phpcbf --standard=ruleset.xml --extensions=php app routes config
tutor_auto_phpcs=${tutor_phpcbf} && ${tutor_phpcs}

social_auth_file_path=./vendor/laravel/socialite/src/Two/AbstractProvider.php
repl_sclite_str='s/$$this->hasInvalidState()/false/g'
disable_socialite_stateless=sed -i $(repl_sclite_str) $(social_auth_file_path) && echo 'fixed login'

tutor_build=composer install && ${disable_socialite_stateless} && php artisan migrate && php artisan storage:link
tutor_update=php artisan migrate && php artisan optimize && php artisan queue:restart && nohup php artisan queue:work --daemon </dev/null >/dev/null 2>&1 &

fix_login:
	@${disable_socialite_stateless}

convention:
	@${tutor_auto_phpcs}

tutor_build:
	@${tutor_build}

tutor_update:
	@${tutor_update}