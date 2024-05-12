<?PHP
	class Emoji {
		var $BaseEmotions = [
			'smiles' => [
				'name' => 'Смайлики',
				'base' => [
					'😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃',
					'😉', '😊', '😇', '🥰', '😍', '🤩', '😘', '😗', '😚', '😙',
					'😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔',
					'🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥',
					'😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮',
					'🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '😎', '🤓',
					'🧐', '😕', '😟', '🙁', '😮', '😯', '😲', '😳', '🥺', '😦',
					'😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞',
					'😓', '😩', '😫', '😤', '😡', '😠', '🤬', '👱', '👲', '👳',
					'👴', '👵', '👶', '👷', '👸', '👼', '💁', '💂', '💃', '💆',
					'😈', '👿', '💀', '💩', '🤡', '👹', '👺', '👻', '👽', '👾',
					'🗿', '🙋', '🙍', '🙎', '🙅', '🙆', '🙏', '👤', '🙇', '👪',
					'👫', '👮', '👯', '👰'
				]
			],
			'animals' => [
				'name' => 'Животные',
				'base' => [
					'😸', '😹', '😺', '😻', '😼', '😽', '😾', '😿', '🙀',
					'🐌', '🐍', '🐎', '🐑', '🐒', '🐔', '🐗', '🐘', '🐙',
					'🐚', '🐛', '🐜', '🐝', '🐞', '🐟', '🐠', '🐡', '🐢',
					'🐣', '🐤', '🐥', '🐦', '🐧', '🐨', '🐩', '🐫', '🐬',
					'🐭', '🐮', '🐯', '🐰', '🐱', '🐲', '🐳', '🐴', '🐵',
					'🐶', '🐷', '🐸', '🐹', '🐺', '🐻', '🐼', '🐽', '🐾',
					'🐀', '🐁', '🐂', '🐃', '🐄', '🐅', '🐆', '🐇', '🐈',
					'🐉', '🐊', '🐋', '🐏', '🐐', '🐓', '🐕', '🐖', '🐪'
				]
			],
			'zodiac' => [
				'name' => 'Знаки зодиака',
				'base' => [
					'♈', '♉', '♊', '♋', '♌', '♍', '♎', '♏', '♐',
					'♑', '♒', '♓'
				]
			],
			'plants' => [
				'name' => 'Растения и еда',
				'base' => [
					'🌱', '🌴', '🌵', '🌷', '🌸', '🌹', '🌺', '🌻', '🌼', '🌽',
					'🌾', '🌿', '🍀', '🍁', '🍂', '🍃', '🍄', '🍅', '🍆', '🍇',
					'🍈', '🍉', '🍊', '🍌', '🍍', '🍎', '🍏', '🍑', '🍒', '🍓',
					'🍔', '🍕', '🍖', '🍗', '🍘', '🍙', '🍚', '🍛', '🍜', '🍝',
					'🍞', '🍟', '🍠', '🍡', '🍢', '🍣', '🍤', '🍥', '🍦', '🍧',
					'🍨', '🍩', '🍪', '🍫', '🍬', '🍭', '🍮', '🍯', '🍰', '🍱',
					'🍲', '🍳', '🍴', '🍵', '🍶', '🍷', '🍸', '🍹', '🍺', '🍻'
				]
			],
			'other' => [
				'name' => 'Разное',
				'base' => [
					'👀', '👂', '👃', '👄', '👅', '👆', '👇', '👈', '👉', '👊',
					'👋', '👌', '👍', '👎', '👏', '👐', '👑', '👒', '👓', '👔',
					'👕', '👖', '👗', '👘', '👙', '👚', '👛', '👜', '👝', '👞',
					'👟', '👠', '👡', '👢', '👣', '💄', '💅', '💇', '💈', '💉',
					'💊', '💋', '💌', '💍', '💎', '💏', '💐', '💑', '💒', '💓',
					'💔', '💕', '💖', '💗', '💘', '💙', '💚', '💛', '💜', '💝',
					'💞', '💟', '💠', '💡', '💢', '💣', '💤', '💥', '💦', '💧',
					'💨', '💩', '💪', '💫', '💬', '💮', '💯', '💰', '💱', '💲',
					'💳', '💴', '💵', '💸', '💹', '💺', '💻', '💼', '💽', '💾',
					'💿', '📀', '📁', '📂', '📃', '📄', '📅', '📆', '📇', '📈',
					'📉', '📊', '📋', '📌', '📍', '📎', '📏', '📐', '📑', '📒',
					'📓', '📔', '📕', '📖', '📗', '📘', '📙', '📚', '📛', '📜',
					'📝', '📞', '📟', '📠', '📡', '📢', '📣', '📤', '📥', '📦',
					'📧', '📨', '📩', '📪', '📫', '📮', '📰', '📱', '📲', '📳',
					'📴', '📶', '📷', '📹', '📺', '📻', '📼', '🔃', '🔊', '🔋',
					'🔌', '🔍', '🔎', '🔏', '🔐', '🔑', '🔒', '🔓', '🔔', '🔖',
					'🔗', '🔘', '🔙', '🔚', '🔛', '🔜', '🔝', '🔞', '🔟', '🔠',
					'🔡', '🔢', '🔣', '🔤', '🔥', '🔦', '🔧', '🔨', '🔩', '🔪',
					'🔫', '🔮', '🔯', '🔰', '🔱', '🔲', '🔳', '🔴', '🔵', '🔶',
					'🔷', '🔸', '🔹', '🔺', '🔻', '🔼', '🔽', '🕐', '🕑', '🕒',
					'🕓', '🕔', '🕕', '🕖', '🕗', '🕘', '🕙', '🕚', '🕛', '🗻',
					'🗼', '🗽', '🗾', '🚁', '🚂', '🚆', '🚈', '🚊', '🚍', '🚎',
					'🚐', '🚔', '🚖', '🚘', '🚛', '🚜', '🚝', '🚞', '🚟', '🚠',
					'🚡', '🚣', '🚦', '🚮', '🚯', '🚰', '🚱', '🚳', '🚴', '🚵',
					'🚷', '🚸', '🚿', '🛁', '🛂', '🛃', '🛄', '🛅', '🌍', '🌎',
					'🌐', '🌒', '🌖', '🌗', '🌘', '🌚', '🌜', '🌝', '🌞', '🌲',
					'🌳', '🍋', '🍐', '🍼', '🏇', '🏉', '🏤', '👥', '👬', '👭',
					'💭', '💶', '💷', '📬', '📭', '📯', '📵', '🔀', '🔁', '🔂',
					'🔄', '🔅', '🔆', '🔇', '🔉', '🔕', '🔬', '🔭', '🕜', '🕝',
					'🕞', '🕟', '🕠', '🕡', '🕢', '🕣', '🕤', '🕥', '🕦', '🕧',
					'✂', '✅', '✈', '✉', '✊', '✋', '✌', '✏', '✒', '✔',
					'✖', '✨', '✳', '✴', '❄', '❇', '❌', '❎', '❓', '❔',
					'❕', '❗', '❤', '➕', '➖', '➗', '➡', '➰', '🚀', '🚃',
					'🚄', '🚅', '🚇', '🚉', '🚌', '🚏', '🚑', '🚒', '🚓', '🚕',
					'🚗', '🚙', '🚚', '🚢', '🚤', '🚥', '🚧', '🚨', '🚩', '🚪',
					'🚫', '🚬', '🚭', '🚲', '🚶', '🚹', '🚺', '🚻', '🚼', '🚽',
					'🚾', '🛀', '©', '®', '‼', '⁉', '™', 'ℹ', '↔', '↕', '↖',
					'↗', '↘', '↙', '↩', '↪', '⌚', '⌛', '⏩', '⏪', '⏫', '⏬',
					'⏰', '⏳', '▪', '▫', '▶', '◀', '◻', '◼', '◽', '◾', '☀',
					'☁', '☎', '☑', '☔', '☕', '☝', '☺', '♠', '♣', '♥', '♦',
					'♨', '♻', '♿', '⚓', '⚠', '⚡', '⚪', '⚫', '⚽', '⚾',
					'⛄', '⛅', '⛎', '⛔', '⛪', '⛲', '⛳', '⛵', '⛺', '⛽',
					'⤴', '⤵', '⬅', '⬆', '⬇', '⬛', '⬜', '⭐', '⭕', '〰', '〽',
					'㊗', '㊙', '🀄', '🃏', '🌀', '🌁', '🌂', '🌃', '🌄', '🌅',
					'🌆', '🌇', '🌈', '🌉', '🌊', '🌋', '🌌', '🌏', '🌑', '🌓',
					'🌔', '🌕', '🌙', '🌛', '🌟', '🌠', '🌰', '🎀', '🎁', '🎂',
					'🎃', '🎄', '🎅', '🎆', '🎇', '🎈', '🎉', '🎊', '🎋', '🎌',
					'🎍', '🎎', '🎏', '🎐', '🎑', '🎒', '🎓', '🎠', '🎡', '🎢',
					'🎣', '🎤', '🎥', '🎦', '🎧', '🎨', '🎩', '🎪', '🎫', '🎬',
					'🎭', '🎮', '🎯', '🎰', '🎱', '🎲', '🎳', '🎴', '🎵', '🎶',
					'🎷', '🎸', '🎹', '🎺', '🎻', '🎼', '🎽', '🎾', '🎿', '🏀',
					'🏁', '🏂', '🏃', '🏄', '🏆', '🏈', '🏊', '🏠', '🏡', '🏢',
					'🏣', '🏥', '🏦', '🏧', '🏨', '🏩', '🏪', '🏫', '🏬', '🏭',
					'🏮', '🏯', '🏰'
				]
			]
		];
		
		public function __construct() {
			$this->BaseEmotions['smiles']['name'] = getLang('emoji_smiles');
			$this->BaseEmotions['animals']['name'] = getLang('emoji_animals');
			$this->BaseEmotions['zodiac']['name'] = getLang('emoji_zodiac');
			$this->BaseEmotions['plants']['name'] = getLang('emoji_plants');
			$this->BaseEmotions['other']['name'] = getLang('emoji_other');
		}
		
		public function Convert($Text) {
			foreach($this->BaseEmotions as $Base) {
				foreach($Base['base'] as $Emotion) {
					$HEX = mb_convert_encoding($Emotion, 'UTF-32', 'UTF-8');
					$HEX = bin2hex($HEX);
					$HEX = hexdec($HEX);
					
					$Text = str_replace($Emotion, "&amp;#$HEX;", $Text);
				}
			}
			
			return $Text;
		}
		
		public function List() {
			$List = '';
			
			foreach($this->BaseEmotions as $Base) {
				$List .= '<label>' . $Base['name'] . '</label>';
				
				foreach($Base['base'] as $Emotion) {
					$List .= '<div data-targer="emoji">' . $Emotion . '</div>';
				}
			}
			
			return $List;
		}
	}