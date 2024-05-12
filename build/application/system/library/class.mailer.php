<?PHP 
	spl_autoload_register(function() {
		$folders = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/phpmailer/'));
		
		foreach($folders as $file) {
			if(!$file->isDir()) {
				require_once($file->getPathname());
			}
		}
	});
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	class Mailer {
		private $mail;
		private $conf;

		public function __construct($create = null) {
			if(isset($create)) {
				$this->mail = new PHPMailer(true);
			}
			
			$this->conf = pdo()->query("SELECT * FROM `config__email` LIMIT 1")->fetch(PDO::FETCH_OBJ);
		}

		public function connect() {
			$this->mail->isSMTP();
			$this->mail->Host			= $this->conf->hostname;
			$this->mail->SMTPAuth		= true;
			$this->mail->Username		= $this->conf->username;
			$this->mail->Password 		= $this->conf->password;
			$this->mail->SMTPSecure		= PHPMailer::ENCRYPTION_SMTPS;
			$this->mail->Port			= $this->conf->port;
			$this->mail->CharSet 		= $this->conf->charset;
			$this->mail->setFrom($this->conf->username, conf()->site_name);

			return $this;
		}

		public function add($email, $name = '') {
			$this->mail->addAddress($email, $name);

			return $this;
		}

		public function form($subject, $body, $altBody = '') {
			$this->mail->isHTML(true);
			$this->mail->Subject 	= $subject;
			$this->mail->Body    	= $body;
			$this->mail->altBody	= $altBody;

			return $this;
		}

		public function send() {
			return $this->mail->send();
		}
	}