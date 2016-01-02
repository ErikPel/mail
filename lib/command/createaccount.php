<?php

/**
 * ownCloud - Mail
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @copyright Christoph Wurst 2016
 */

namespace OCA\Mail\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OCA\Mail\Db\MailAccount;
use OCA\Mail\Service\AccountService;

class CreateAccount extends Command {

	const ARGUMENT_USER_ID = 'user_id';
	const ARGUMENT_NAME = 'name';
	const ARGUMENT_EMAIL = 'email';
	const ARGUMENT_IMAP_HOST = 'imap_host';
	const ARGUMENT_IMAP_PORT = 'imap_port';
	const ARGUMENT_IMAP_SSL_MODE = 'imap_ssl_mode';
	const ARGUMENT_IMAP_USER = 'imap_user';
	const ARGUMENT_IMAP_PASSWORD = 'imap_password';
	const ARGUMENT_SMTP_HOST = 'smtp_host';
	const ARGUMENT_SMTP_PORT = 'smtp_port';
	const ARGUMENT_SMTP_SSL_MODE = 'smtp_ssl_mode';
	const ARGUMENT_SMTP_USER = 'smtp_user';
	const ARGUMENT_SMTP_PASSWORD = 'smtp_password';

	/** @var AccountService */
	private $accountService;

	public function __construct(AccountService $service) {
		parent::__construct();

		$this->accountService = $service;
	}

	protected function configure() {
		$this->setName('mail:account:create');
		$this->setDescription('creates IMAP account');
		$this->addArgument(self::ARGUMENT_USER_ID, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_NAME, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_EMAIL, InputArgument::REQUIRED);

		$this->addArgument(self::ARGUMENT_IMAP_HOST, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_PORT, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_SSL_MODE, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_USER, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_PASSWORD, InputArgument::REQUIRED);

		$this->addArgument(self::ARGUMENT_SMTP_HOST, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_PORT, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_SSL_MODE, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_USER, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_PASSWORD, InputArgument::REQUIRED);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$userId = $input->getArgument(self::ARGUMENT_USER_ID);
		$name = $input->getArgument(self::ARGUMENT_NAME);
		$email = $input->getArgument(self::ARGUMENT_EMAIL);

		$imapHost = $input->getArgument(self::ARGUMENT_IMAP_HOST);
		$imapPort = $input->getArgument(self::ARGUMENT_IMAP_PORT);
		$imapSslMode = $input->getArgument(self::ARGUMENT_IMAP_SSL_MODE);
		$imapUser = $input->getArgument(self::ARGUMENT_IMAP_USER);
		$imapPassword = $input->getArgument(self::ARGUMENT_IMAP_PASSWORD);

		$smtpHost = $input->getArgument(self::ARGUMENT_SMTP_HOST);
		$smtpPort = $input->getArgument(self::ARGUMENT_SMTP_PORT);
		$smtpSslMode = $input->getArgument(self::ARGUMENT_SMTP_SSL_MODE);
		$smtpUser = $input->getArgument(self::ARGUMENT_SMTP_USER);
		$smtpPassword = $input->getArgument(self::ARGUMENT_SMTP_PASSWORD);

		$account = new MailAccount();
		$account->setUserId($userId);
		$account->setName($name);
		$account->setEmail($email);

		$account->setInboundHost($imapHost);
		$account->setInboundPort($imapPort);
		$account->setInboundSslMode($imapSslMode);
		$account->setInboundUser($imapUser);
		$account->setInboundPassword($imapPassword);

		$account->setOutboundHost($smtpHost);
		$account->setOutboundPort($smtpPort);
		$account->setOutboundSslMode($smtpSslMode);
		$account->setOutboundUser($smtpUser);
		$account->setOutboundPassword($smtpPassword);

		$this->accountService->save($account);

		$output->writeln("<info>Account $email created");
	}

}
