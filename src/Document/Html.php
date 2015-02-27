<?php


	namespace LiftKit\Document;
	
	use LiftKit\Output\Html as HtmlOutput;


	class Html
	{
		/**
		 * @var array
		 * @access protected
		 */
		protected $headers = array();


		/**
		 * @var array
		 * @access protected
		 */
		protected $prependBody = array();


		/**
		 * @var array
		 * @access protected
		 */
		protected $appendBody = array();


		/**
		 * addHeader function.
		 *
		 * @access public
		 *
		 * @param string $string
		 *
		 * @return void
		 */
		public function addHeader ($string, $prepend = false)
		{
			if ($prepend) {
				$this->prependHeader($string);
			} else {
				$this->headers[] = $string;
			}
		}

		
		/**
		 * prependHeader function.
		 *
		 * Prepend a document headers
		 *
		 * @access public
		 *
		 * @param string $string
		 *
		 * @return void
		 */
		public function prependHeader ($string)
		{
			array_unshift($this->headers, $string);
		}


		/**
		 * addMeta function.
		 *
		 * Add meta tags to head.
		 *
		 * @access public
		 *
		 * @param string $name
		 * @param string $content
		 * @param array  $attributes (default: array())
		 *
		 * @return void
		 */
		public function addMeta ($name, $content, $attributes = array())
		{
			$html = '<meta name="' . $this->sanitize($name) . '" content="' . $this->sanitize($content) . '"';

			foreach ($attributes as $key => $value) {
				$html .= ' ' . $this->sanitize($key) . '="' . $this->sanitize($value) . '"';
			}

			$html .= ' />';

			$this->addHeader($html);
		}


		/**
		 * addScript function.
		 *
		 * Adds scripts to head.
		 *
		 * @access public
		 *
		 * @param string $src
		 * @param bool   $prepend (default: false)
		 *
		 * @return void
		 */
		public function addScript ($src, $prepend = false)
		{
			$html = '<script type="text/javascript" src="' . $this->sanitize($src) . '"></script>';

			$this->addHeader($html, $prepend);
		}


		/**
		 * addStylesheet function.
		 *
		 * Addes stylesheets to head.
		 *
		 * @access public
		 *
		 * @param string $src
		 * @param bool   $prepend (default: false)
		 *
		 * @return void
		 */
		public function addStylesheet ($src, $prepend = false)
		{
			$html = '<link rel="stylesheet" type="text/css" href="' . $this->sanitize($src) . '" />';

			$this->addHeader($html, $prepend);
		}


		/**
		 * getHeaders function.
		 *
		 * Returns elements to be inserted to into view.
		 *
		 * @return string
		 */
		public function getHeaders ()
		{
			return implode(PHP_EOL, $this->headers);
		}


		/**
		 * removeHeaders function.
		 *
		 * Clears headers array.
		 *
		 * @access public
		 * @return void
		 */
		public function removeHeaders ()
		{
			$this->headers = array();
		}


		/**
		 * setTitle function.
		 *
		 * Sets document browser title.
		 *
		 * @access public
		 *
		 * @param mixed $title
		 *
		 * @return void
		 */
		public function setTitle ($title)
		{
			$html            = '<title>' . $this->sanitize($title) . '</title>';
			$this->headers[] = $html;
		}


		/**
		 * prependToBody function.
		 *
		 * Prepends html to body tag.
		 *
		 * @access public
		 *
		 * @param mixed $html
		 *
		 * @return void
		 */
		public function prependToBody ($html)
		{
			$this->prependBody[] = $html;
		}


		/**
		 * getPrependedElements function.
		 *
		 * Returns elements prepended to body.
		 *
		 * @return string
		 */
		public function getPrependedElements ()
		{
			return implode(PHP_EOL, $this->prependBody);
		}


		/**
		 * appendToBody function.
		 *
		 * Appends html to body tag.
		 *
		 * @access public
		 * @param mixed $html
		 * @return void
		 */
		public function appendToBody ($html)
		{
			$this->appendBody[] = $html;
		}


		/**
		 * getAppendedElements function.
		 *
		 * Returns elements appended to body.
		 *
		 * @access public
		 * @return mixed
		 */
		public function getAppendedElements ()
		{
			return implode(PHP_EOL, $this->appendBody);
		}
		
		
		/**
		 * Sanitizes HTML output
		 *
		 * @param string $string
		 */
		protected function sanitize ($string)
		{
			return HtmlOutput::sanitize($string);
		}
	}