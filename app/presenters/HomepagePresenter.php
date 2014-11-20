<?php

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Utils\Strings;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	public function renderDefault($hash = NULL)
	{
		if ($hash)
		{
			$latinText = $this->session->getSection('runes')->$hash;
			$this->template->hasData = TRUE;
			$this->template->latinText = $latinText;
			$this->template->runeText = $this->latin2runes(strtolower(Strings::toAscii($latinText)));
		}
		else
		{
			$this->template->hasData = FALSE;
		}
	}
	
	protected function createComponentInputForm()
	{
		$form = new Form;
		$form->addTextArea('text', "Text")
				->addRule(Form::FILLED, "Zadejte text, který má být přeložen.");
		$form->addSubmit('ok', "Přeložit");
		$form->onSuccess[] = $this->formSuccess;
		
		return $form;
	}
	
	public function formSuccess(Form $form)
	{
		$latinText = $form->values->text;
		$hash = hash('md5', $latinText);
		$this->session->getSection('runes')->$hash = $latinText;
		$this->redirect('this', $hash);
		
	}
	
	protected function latin2runes($latinText)
	{
		$runeTable = [
			'a' => "ᚫ",
			'b' => "ᛒ",
			'c' => "ᚲ",
			'd' => "ᛞ",
			'e' => "ᛖ",
			'f' => "ᚠ",
			'g' => "ᚷ",
			'h' => "ᚺ",
			'i' => "ᛁ",
			'j' => "ᛃ",
			'k' => "ᚲ",
			'l' => "ᛚ",
			'm' => "ᛗ",
			'n' => "ᚾ",
			'o' => "ᛟ",
			'p' => "ᛈ",
			'q' => "ᛜ", # Ng
			'r' => "ᚱ",
			's' => "ᛋ",
			't' => "ᛏ",
			'u' => "ᚢ",
			'v' => "ᚹ",
			'w' => "ᚹ",
			'x' => "ᚦ", # Th
			'y' => "ᛇ", # Ei, Ae
			'z' => "ᛉ",
			' ' => " ",
//			',' => "᛬",
			'.' => "᛫"
		];
		
		$runeText = "";
		
		foreach(str_split($latinText) as $char) {
			if(ord($char) === 10) { # zachováme odřádkování
				$runeText .= $char;
			}
			else {
				if (isset($runeTable[$char]))
					$runeText .= $runeTable[$char];
			}
		}
		
		return $runeText;
	}

}
