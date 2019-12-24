<?php

require_once __DIR__.'/vendor/autoload.php';

use Carbon\Carbon;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Tools extends CLI
{
    /**
     * Register options and arguments.
     *
     * @param  Options  $options
     */
    protected function setup(Options $options)
    {
        $options->setHelp('A simple JWT token generator.');

        $options->registerCommand('jwt', 'Generate a JWT token.');
        $options->registerOption(
            'secret',
            'The JWT token secret key.',
            's',
            true,
            'jwt'
        );
        $options->registerOption(
            'issuer',
            'The JWT token issuer (iss).',
            'i',
            true,
            'jwt'
        );
        $options->registerOption(
            'scopes',
            'The JWT token scope (scope). Eg: appointment,foo,bar',
            'c',
            true,
            'jwt'
        );
        $options->registerOption(
            'expire_in',
            'The no. of days before the token expires.',
            'e',
            true,
            'jwt'
        );
        $options->registerOption(
            'timezone',
            'The timezone to use when generating a token..',
            'l',
            false,
            'jwt'
        );
        $options->registerOption(
            'client_id',
            'The JWT token client id (client_id).',
            'l',
            false,
            'jwt'
        );
    }

    /**
     * Main.
     *
     * @param  Options  $options
     */
    protected function main(Options $options)
    {
        switch ($options->getCmd()) {
            case 'jwt':
                $this->success($this->generateToken($options));
                break;
            default:
                $this->error('Please choose a command first.');
                echo $options->help();
                exit;
        }
    }

    /**
     * Generate token.
     *
     * @param  Options  $options
     *
     * @return Builder|Token
     */
    private function generateToken(Options $options)
    {
        $timezone = $options->getOpt('timezone', null) ?? 'UTC';

        $expireAt = Carbon::now($timezone)
            ->addDays($options->getOpt('expire_in'))
            ->toDateTimeString();

        $token = (new Builder())
            ->withClaim('iss', $options->getOpt('issuer'))
            ->withClaim('exp', $expireAt)
            ->withClaim('scope', $options->getOpt('scopes'));

        if ($options->getOpt('client_id')) {
            $token->withClaim('client_id', (int) $options->getOpt('client_id'));
        }

        $token = $token->getToken(new Sha256(), new Key($options->getOpt('secret')));

        return $token;
    }
}

// Run
$cli = new Tools();
$cli->run();
