<?php
namespace Acme\Bundle\DemoDataFlowBundle\Job;

use Oro\Bundle\DataFlowBundle\Job\AbstractJob;
use Acme\Bundle\DemoDataFlowBundle\Transform\CustomerTransformer;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;

use Ddeboer\DataImport\Source\Stream;
use Ddeboer\DataImport\Reader\CsvReader;

/**
 * Import customers from Magento database
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportCustomersJob extends AbstractJob
{

    /**
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * @param FlexibleManager $manager
     */
    public function __construct(FlexibleManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Process
     *
     * @return multitype
     */
    public function run()
    {
        $messages = array();

        // Call reader
        $stream = new Stream($this->getConfiguration()->getFilePath());
        $csvReader = new CsvReader(
            $stream->getFile(),
            $this->getConfiguration()->getDelimiter(),
            $this->getConfiguration()->getEnclosure(),
            $this->getConfiguration()->getEscape()
        );
        $csvReader->setHeaderRowNumber(0);

        // Call transformer
        $transformer = new CustomerTransformer($this->manager);
        $customers = array();
        foreach ($csvReader as $customerItem) {
            $customer = $transformer->transform($customerItem);
            $customers[] = $customer;
            $this->manager->getStorageManager()->persist($customer);
        }

        // TODO : Call loader to persist customers
        $this->manager->getStorageManager()->flush();

        return $customers;
    }



    /**
     * Get configuration
     * @return \Acme\Bundle\DemoDataFlowBundle\Configuration\NewMagentoConfiguration
     */
    public function getNewConfigurationInstance()
    {
        // TODO : inject existing ?
        return new \Acme\Bundle\DemoDataFlowBundle\Configuration\NewCsvConfiguration();
    }


    /**
     * Get form
     * @return string
     */
    public function getFormId()
    {
        return "connector.form.csv";
    }

    /**
     * Get form handler
     * @return string
     */
    public function getFormHandlerId()
    {
        return "oro_dataflow.form.handler.configuration";
    }

}
