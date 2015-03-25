<?php


	namespace LiftKit\Tests\Unit\Document;

	use LiftKit\Document\Html as HtmlDocument;
	use LiftKit\Output\Html;
	use PHPUnit_Framework_TestCase as TestCase;


	class HtmlTest extends TestCase
	{
		/**
		 * @var HtmlDocument
		 */
		protected $document;


		public function setUp ()
		{
			$this->document = new HtmlDocument;
		}


		/**
		 * Long test helps test ordering is proper
		 */
		public function testHeaders ()
		{
			$scriptHeader = '<script>alert("hello");</script>';
			$this->document->addHeader($scriptHeader);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getHeaders()),
				$this->normalizeHtml($scriptHeader)
			);

			$metaName = '&dks;<';
			$metaContent = "&%/><";
			$attr = '$<>&sl';

			$attributes = array(
				'attr' => $attr,
			);

			$metaCompare = '<meta name="' . Html::sanitize($metaName) . '" content="' . Html::sanitize($metaContent) . '" attr="' . Html::sanitize($attr) . '" />';

			$this->document->addMeta($metaName, $metaContent, $attributes);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getHeaders()),
				$this->normalizeHtml($scriptHeader . ' ' . $metaCompare)
			);

			$prepended = '<link rel="test" />';

			$this->document->prependHeader($prepended);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getHeaders()),
				$this->normalizeHtml($prepended . ' ' . $scriptHeader . ' ' . $metaCompare)
			);

			$scriptSrc = '&adsd';
			$scriptCompare = '<script type="text/javascript" src="' . Html::sanitize($scriptSrc) . '"></script>';

			$this->document->addScript($scriptSrc);
			$this->document->addScript($scriptSrc, true);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getHeaders()),
				$this->normalizeHtml($scriptCompare . ' ' . $prepended . ' ' . $scriptHeader . ' ' . $metaCompare . ' ' . $scriptCompare)
			);

			$cssHref = '&?></';
			$cssCompare = '<link rel="stylesheet" type="text/css" href="' . Html::sanitize($cssHref) . '" />';

			$this->document->addStylesheet($cssHref);
			$this->document->addStylesheet($cssHref, true);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getHeaders()),
				$this->normalizeHtml($cssCompare . ' ' . $scriptCompare . ' ' . $prepended . ' ' . $scriptHeader . ' ' . $metaCompare . ' ' . $scriptCompare . ' ' . $cssCompare)
			);

			$title = '<&asdasd&;';
			$titleCompare = '<title>' . Html::sanitize($title) . '</title>';

			$this->document->setTitle($title);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getHeaders()),
				$this->normalizeHtml($cssCompare . ' ' . $scriptCompare . ' ' . $prepended . ' ' . $scriptHeader . ' ' . $metaCompare . ' ' . $scriptCompare . ' ' . $cssCompare . ' ' . $titleCompare)
			);

			$this->document->removeHeaders();

			$this->assertEquals(
				$this->document->getHeaders(),
				''
			);
		}


		public function testPrependedElements ()
		{
			$element1 = '<div class="elem1"></div>';
			$element2 = '<div class="elem2"></div>';

			$this->document->prependToBody($element1);
			$this->document->prependToBody($element2);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getPrependedElements()),
				$this->normalizeHtml($element1 . ' ' . $element2)
			);
		}


		public function testAppendedElements ()
		{
			$element1 = '<div class="elem1"></div>';
			$element2 = '<div class="elem2"></div>';

			$this->document->appendToBody($element1);
			$this->document->appendToBody($element2);

			$this->assertEquals(
				$this->normalizeHtml($this->document->getAppendedElements()),
				$this->normalizeHtml($element1 . ' ' . $element2)
			);
		}


		public function testGetScriptUrls ()
		{
			$script1 = 'sample1';
			$script2 = 'sample2';

			$this->document->addScript($script1);
			$this->document->addScript($script2, true);

			$this->assertEquals(
				array(
					$script2,
					$script1,
				),
				$this->document->getScriptUrls()
			);
		}


		public function testGetStylesheetUrls ()
		{
			$stylesheet1 = 'sample1';
			$stylesheet2 = 'sample2';

			$this->document->addStylesheet($stylesheet1);
			$this->document->addStylesheet($stylesheet2, true);

			$this->assertEquals(
				array(
					$stylesheet2,
					$stylesheet1,
				),
				$this->document->getStylesheetUrls()
			);
		}


		protected function normalizeHtml ($htmlString)
		{
			return trim(preg_replace('#\s+#', ' ', $htmlString));
		}
	}