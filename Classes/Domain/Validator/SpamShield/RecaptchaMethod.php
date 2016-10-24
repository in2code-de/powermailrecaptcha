<?php
namespace In2code\Powermailrecaptcha\Domain\Validator\SpamShield;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RecaptchaMethod
 * @package In2code\Powermailrecaptcha\Domain\Validator\SpamShield
 */
class RecaptchaMethod extends AbstractMethod
{

    /**
     * @var string
     */
    protected $secretKey = '';

    /**
     * Check if secret key is given and set it
     * 
     * @throws \Exception
     */
    public function initialize()
    {
        if ($this->formHasRecaptcha()) {
            if (empty($this->configuration['secretkey']) || $this->configuration['secretkey'] === 'abcdef') {
                throw new \Exception('No secretkey given. Please add a secret key to TypoScript Constants');
            }
            $this->secretKey = $this->configuration['secretkey'];
        }
    }

    /**
     * @return bool true if spam recognized
     */
    public function spamCheck()
    {
        if (!$this->formHasRecaptcha() || $this->skipCaptchaCheck()) {
            return false;
        }
        if ($this->getCaptchaResponse()) {
            $jsonResult = GeneralUtility::getUrl($this->getSiteVerifyUri());
            $result = json_decode($jsonResult);
            return !$result->success;
        }
        return true;
    }

    /**
     * Check if current form has a recaptcha field
     *
     * @return bool
     */
    protected function formHasRecaptcha()
    {
        foreach ($this->mail->getForm()->getPages() as $page) {
            /** @var Field $field */
            foreach ($page->getFields() as $field) {
                if ($field->getType() === 'recaptcha') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return string
     */
    protected function getSiteVerifyUri()
    {
        return 'https://www.google.com/recaptcha/api/siteverify' .
            '?secret=' . $this->secretKey . '&response=' . $this->getCaptchaResponse();
    }

    /**
     * @return string|false
     */
    protected function getCaptchaResponse()
    {
        $response = GeneralUtility::_GP('g-recaptcha-response');
        if (!empty($response)) {
            return $response;
        }
        return false;
    }

    /**
     * Captcha check should be skipped on createAction if there was a confirmationAction where the captcha was
     * already checked before
     * Note: $this->flexForm is only available in powermail 3.9 or newer
     *
     * @return bool
     */
    protected function skipCaptchaCheck()
    {
        if (property_exists($this, 'flexForm')) {
            $confirmationActive = $this->flexForm['settings']['flexform']['main']['confirmation'] === '1';
            return $this->getActionName() === 'create' && $confirmationActive;
        }
        return false;
    }

    /**
     * @return string "confirmation" or "create"
     */
    protected function getActionName()
    {
        $pluginVariables = GeneralUtility::_GPmerged('tx_powermail_pi1');
        return $pluginVariables['action'];
    }
}
