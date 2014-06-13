<?
class DOMTools{
	static function isolateNode($node){
		$dom = new DOMDocument;
		if(get_class($node) == 'DOMDocument'){//DOMDocument is not a node, so get primary element in document
			$node = $node->documentElement;
		}
		$dom->appendChild($dom->importNode($node,true));
		return $dom;
	}
	static function nodeInnerXml($node){
		$children = $node->childNodes; 
		return self::nodesHtml($children);
    }
    //either and array of nodes or a nodeList
    static function nodesHtml($nodes){
		foreach($nodes as $node){
			$html .= trim(self::nodeHtml($node));
		}
		return $html;
	}
	static function nodeHtml($node){
		$dom = self::isolateNode($node);
		return $dom->saveHTML();
	}
	static function loadHtml($html){
		$dom = new DOMDocument;
		@$dom->loadHTML($html);
		$xpath = new DomXPath($dom);
		return array($dom,$xpath);
	}
	static function nodeXml($node){
		$dom = self::isolateNode($node);
		return $dom->saveXML();
	}
	static function loadXml($xml,$nsPrefix='d'){
		$dom = new DOMDocument;
		@$dom->loadXML($xml);
		$xpath = new DomXPath($dom);
		
		$rootNamespace = $dom->lookupNamespaceUri($dom->namespaceURI);
		if($rootNamespace){
			if($dom->documentElement->getAttribute('xmlns:d')){
				Debug::throwError('Namespace prefix "'.$nsPrefix.'" taken');
			}
			$xpath->registerNamespace($nsPrefix, $rootNamespace);
			$nsPrefix .= ':';
		}else{
			$nsPrefix = '';
		}
		return array($dom,$xpath,$nsPrefix);
	}
	static function removeTextNodes($nodeList){
		for($i = 0; $i < $nodeList->length; $i++){
			if($nodeList->item($i)->nodeName != '#text'){
				$nonTextNodes[] = $nodeList->item($i);
			}
		}
		return $nonTextNodes;
	}
}
