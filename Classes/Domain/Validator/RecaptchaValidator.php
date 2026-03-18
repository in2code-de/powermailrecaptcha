<?php

declare(strict_types=1);

namespace In2code\Powermailrecaptcha\Domain\Validator;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Domain\Validator\AbstractValidator;
use In2code\Powermail\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Error\Result;

class RecaptchaValidator extends AbstractValidator
{

    protected ?Mail $mail = null;
    protected string $secretKey = '';

    /**
     * @param mixed $value
     * @return Result
     * @throws \In2code\Powermail\Exception\DeprecatedException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function validate(mixed $value): Result
    {
        $this->result = new Result();
        $this->mail = $value;

        if ($this->isFormWithRecaptchaField() && !$this->isCaptchaCheckToSkip()) {
            if (!isset($this->configuration['secretkey']) || $this->configuration['secretkey'] === 'abcdef') {
                throw new \LogicException(
                    'No secretkey given. Please add a secret key to TypoScript Constants',
                    1607012762
                );
            }

            $this->secretKey = $this->configuration['secretkey'];
            $this->isValid($this->getRecaptchaField());
        }

        return $this->result;
    }

    protected function isValid(mixed $value): void
    {
        if (!$value->isMandatory()) {
            return;
        }

        if ($this->getCaptchaResponse() !== '') {
            $jsonResult = GeneralUtility::getUrl($this->getSiteVerifyUri());
            $result = json_decode($jsonResult);
            if ($result->success) {
                return;
            }
        }

        $this->setErrorAndMessage($value, LocalizationUtility::translate('captchaError', 'powermailrecaptcha'));
    }

    /**
     * Check if current form has a recaptcha field
     *
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    protected function isFormWithRecaptchaField(): bool
    {
        foreach ($this->mail->getForm()->getPages() as $page) {
            foreach ($page->getFields() as $field) {
                if ($field->getType() === 'recaptcha') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return Field|null
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    protected function getRecaptchaField(): ?Field
    {
        foreach ($this->mail->getForm()->getPages() as $page) {
            foreach ($page->getFields() as $field) {
                if ($field->getType() === 'recaptcha') {
                    return $field;
                }
            }
        }

        return null;
    }

    protected function getSiteVerifyUri(): string
    {
        return 'https://www.google.com/recaptcha/api/siteverify' .
            '?secret=' . $this->secretKey . '&response=' . $this->getCaptchaResponse();
    }

    /**
     * @return string|false
     */
    protected function getCaptchaResponse(): string
    {
        $response = $this->request->getParsedBody()['g-recaptcha-response'] ?? $this->request->getQueryParams()['g-recaptcha-response'] ?? null;
        if (!empty($response)) {
            return $response;
        }

        return '';
    }

    /**
     * Captcha check should be skipped on createAction if there was a confirmationAction where the captcha was
     * already checked before
     * Note: $this->flexForm is only available in powermail 3.9 or newer
     */
    protected function isCaptchaCheckToSkip(): bool
    {
        if (property_exists($this, 'flexForm')) {
            $action = $this->getActionName();
            $confirmationActive = $this->flexForm['settings']['flexform']['main']['confirmation'] === '1';
            $optinActive = $this->flexForm['settings']['flexform']['main']['optin'] === '1';
            if ($action === 'create' && $confirmationActive || $action === 'checkCreate' && $confirmationActive) {
                return true;
            }

            if ($action === 'optinConfirm' && $optinActive) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string "confirmation" or "create"
     */
    protected function getActionName(): string
    {
        $pluginVariables = (array) ($this->request->getQueryParams()['tx_powermail_pi1'] ?? []);
        ArrayUtility::mergeRecursiveWithOverrule($pluginVariables, (array) ($this->request->getParsedBody()['tx_powermail_pi1'] ?? []));
        return $pluginVariables['action'];
    }
}
