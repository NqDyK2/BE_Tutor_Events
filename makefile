SCRIPT_VERSION=v1.0
SCRIPT_AUTHOR=Theldph15731
social_auth_file_path=./vendor/laravel/socialite/src/Two/AbstractProvider.php
repl_sclite_str='s/$$this->hasInvalidState()/false/g'
tutor_phpcs=vendor/bin/phpcs --standard=ruleset.xml --extensions=php app routes config
tutor_phpcbf=vendor/bin/phpcbf --standard=ruleset.xml --extensions=php app routes config
tutor_auto_phpcs=${tutor_phpcbf} && ${tutor_phpcs}

dsb_slite_stateless:
	sed -i $(repl_sclite_str) $(social_auth_file_path)

unit:
	@${tutor_auto_phpcs}