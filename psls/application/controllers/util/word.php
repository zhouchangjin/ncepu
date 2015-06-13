<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
const wdFormatDocument                    =  0;
const wdFormatDocument97                  =  0;
const wdFormatDocumentDefault             = 16;
const wdFormatDOSText                     =  4;
const wdFormatDOSTextLineBreaks           =  5;
const wdFormatEncodedText                 =  7;
const wdFormatFilteredHTML                = 10;
const wdFormatFlatXML                     = 19;
const wdFormatFlatXMLMacroEnabled         = 20;
const wdFormatFlatXMLTemplate             = 21;
const wdFormatFlatXMLTemplateMacroEnabled = 22;
const wdFormatHTML                        =  8;
const wdFormatPDF                         = 17;
const wdFormatRTF                         =  6;
const wdFormatTemplate                    =  1;
const wdFormatTemplate97                  =  1;
const wdFormatText                        =  2;
const wdFormatTextLineBreaks              =  3;
const wdFormatUnicodeText                 =  7;
const wdFormatWebArchive                  =  9;
const wdFormatXML                         = 11;
const wdFormatXMLDocument                 = 12;
const wdFormatXMLDocumentMacroEnabled     = 13;
const wdFormatXMLTemplate                 = 14;
const wdFormatXMLTemplateMacroEnabled     = 15;
const wdFormatXPS                         = 18;
const wdFormatOfficeDocumentTemplate      = 23;
const wdFormatMediaWiki                   = 24;
#COM方式转DOC文档
function word2html_com($input,$output){
	$word = new COM("word.application",NULL, CP_UTF8) or die("Unable to instanciate Word");
	$word->Documents->Open($input,false,true);
	$word->Visible = 1;
	$word->ActiveDocument->SaveAs($output,wdFormatXML);
	$content = file_get_contents($output);
	$content=str_replace("<?mso-application progid=\"Word.Document\"?>", "", $content);
	//$content = (string) $word->ActiveDocument->Content;
	//echo $content;
	parseXMLWord($content);
	$word->Documents->close(true);
	$word->Quit();
	$word = null;
	unset($word);
}

function parseXMLWord($content){
	$xml = new DomDocument(); //创建一个新的 DOM对象
	$xml->loadXML($content);
	$body= $xml->getElementsByTagName("body")->item(0);
	$list=$body->childNodes;
	/// sections
	echo "<style>body{background:white}</style>";
	for($i=0;$i<$list->length;$i++){
		$sec= $list->item($i);
		$p=$sec->childNodes;
		 for($j=0;$j<$p->length;$j++){
		 	$pagra=$p->item($j);
		 	$name=$pagra->nodeName;
		 	if($name=="w:p"){
		 		echo processParagraph($pagra);
		 		
		 	}else if($name=="w:tbl"){
		 		$tableContent="<table border='1' cellspacing=0 style='border:solid 1px;width:100%'>";
		 		$children=$pagra->childNodes;
		 		for($n=0;$n<$children->length;$n++){
		 			$tableNode=$children->item($n);
		 			if($tableNode->nodeName=="w:tr"){
		 				$tableContent.="<tr>";
		 				$rowNodes=$tableNode->childNodes;
		 				 for($o=0;$o<$rowNodes->length;$o++){
		 				 	$cellNode=$rowNodes->Item($o);
		 				 		if($cellNode->nodeName=="w:tc"){
		 				 			$tableContent.="<td style='color:black#style#'>";
		 				 			$pagras=$cellNode->childNodes;
		 				 			for($q=0;$q<$pagras->length;$q++){
		 				 				$pagra=$pagras->item($q);
		 				 				if($pagra->nodeName=="w:p"){
		 				 					$tableContent.=processParagraph($pagra);
		 				 				}else if($pagra->nodeName=="w:tcPr"){
		 				 					$tdStyle="";
		 				 					$tcStyle=$pagra->childNodes;
		 				 					for($r=0;$r<$tcStyle->length;$r++){
		 				 						$tcStyleNode=$tcStyle->item($r);
		 				 						if($tcStyleNode->nodeName=="w:tcW"){
		 				 							if($tcStyleNode->getAttribute("w:w")){
		 				 								$value=doubleval($tcStyleNode->getAttribute("w:w"));
		 				 								$value=$value/20/12.8;
		 				 								$tdStyle.=";width:".$value."%";
		 				 							}
		 				 						}
		 				 					}
		 				 					$tableContent=str_replace("#style#", $tdStyle, $tableContent);
		 				 				}
		 				 			}
		 				 			$tableContent.="</td>";
		 				 		}
		 				 }
		 				$tableContent.="</tr>";
		 			}
		 		}
		 		$tableContent.="</table>";
		 		echo $tableContent;
		 	}
		 }
	}
	//echo json_encode($body);
}

function processParagraph($pagra){
	$children=$pagra->childNodes;
	$pagraDivContent="<div style='color:black#style#'>";
	$pStyleContent="";
	for($k=0;$k<$children->length;$k++){
		$node=$children->item($k);
		if($node->nodeName=="w:r"){
			$styleContent="";
			$divContent="<span style='color:black#style#'>";
			$textNodes=$node->childNodes;
			for($l=0;$l<$textNodes->length;$l++){
				$textNode=$textNodes->item($l);
				if($textNode->nodeName=="w:t"){
					$divContent.= str_replace(" ", "&nbsp;", $textNode->nodeValue);
				}else if ($textNode->nodeName=="w:rPr"){
					$styleNodes=$textNode->childNodes;
					for($m=0;$m<$styleNodes->length;$m++){
						$styleNode=$styleNodes->item($m);
						if($styleNode->nodeName=="w:b"){
							$styleContent.=";font-weight:bold";
						}else if($styleNode->nodeName=="w:sz"){
							$styleContent.=";font-size:".(intval($styleNode->getAttribute("w:val"))-10)."px";
						}
					}
				}
			}
			$divContent.= "</span>";
			$pagraDivContent.=str_replace("#style#", $styleContent, $divContent);
		}else if($node->nodeName=="w:pPr"){
			$textNodes=$node->childNodes;
			for($l=0;$l<$textNodes->length;$l++){
				$textNode=$textNodes->item($l);
				if ($textNode->nodeName=="w:jc"){
					$pStyleContent.=";text-align:".$textNode->getAttribute("w:val");
				}else if ($textNode->nodeName=="w:rPr"){
					$styleNodes=$textNode->childNodes;
					for($m=0;$m<$styleNodes->length;$m++){
						$styleNode=$styleNodes->item($m);
						if($styleNode->nodeName=="w:b"){
							$pStyleContent.=";font-weight:bold";
						}else if($styleNode->nodeName=="w:sz"){
							$pStyleContent.=";font-size:".(intval($styleNode->getAttribute("w:val"))-10)."px";
						}
					}
				}else if($textNode->nodeName=="w:ind"){
					
					if($textNode->getAttribute("w:first-line-chars")){
						$value=doubleval($textNode->getAttribute("w:first-line-chars"))/45;
						$pStyleContent.=";text-indent:".$value."%";
					}
					
				}else if($textNode->nodeName=="w:spacing"){
					
					if($textNode->getAttribute("w:line")){
						$value=doubleval($textNode->getAttribute("w:line"))/35;
						$pStyleContent.=";margin-top:".$value."px";
					}
					
				}
			}
		}
	}
	$pagraDivContent.="</div>";
	return str_replace("#style#",$pStyleContent, $pagraDivContent);
}