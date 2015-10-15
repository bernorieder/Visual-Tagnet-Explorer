<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>Instagram Hashtag Explorer</title>
	
	<link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

<table>
	<tr>
		<td>
			
			<h1>Frequently Asked Questions</h1>
			
			<h2>What is this?</h2>
			
			<p>Instagram Hashtag Explorer is a small script that connects to the Instagram API with a user's credentials, retrieves either the latest media tagged
			with a specified term (via the <a href="https://instagram.com/developer/endpoints/tags/#get_tags_media_recent">/tags/../media/recent</a> API call) or media
			around a particular location (via the <a href="https://instagram.com/developer/endpoints/media/#get_media_search">/media/search</a> API call), and creates
			a number of files to analyze in standard software.</p>
			
			
			<h2>What kind of files does Instagram Hashtag Explorer generate?</h2>
			
			<p>It creates network files in <a href="http://guess.wikispot.org/The_GUESS_.gdf_format" target="_blank">gdf format</a> (a simple text format that specifies a graph) as well as
			statistical files using a <a href="http://en.wikipedia.org/wiki/Tab-separated_values">tab-separated format</a>. You can easily change TSV to CSV by searching and replacing all tabs with commas.</p>
			
			<p>These files can then be analyzed and visualized using graph visualization software such as the powerful and very easy to use <a href="http://gephi.org/" target="_blank">gephi</a>
			platform or statistical tools such as R, Excel, SPSS, etc.</p>
			
			
			<h2>Who develops Instagram Hashtag Explorer?</h2>
			
			<p>Instagram Hashtag Explorer is written and maintained by <a href="http://rieder.polsys.net">Bernhard Rieder</a>, Associate Professor in <a href="http://mediastudies.nl" target="_blank">Media Studies</a> at the
			<a href="http://www.uva.nl">University of Amsterdam</a> and researcher at the <a href="https://www.digitalmethods.net" target="_blank">Digital Methods Initiative</a>.</p>
			
			<p>I announce changes or new modules on <a href="https://twitter.com/RiederB/" target="_blank">@RiederB</a>, but I do not react to any tool related matters on channels other than <a href="mailto:tools@polsys.net">tools@polsys.net</a>.</p>
			
			<p>You can find some of my other software <a href="http://labs.polsys.net">here</a>.</p>
			
			
			<h2>How can I cite Instagram Hashtag Explorer?</h2>
			
			<p>There is currently no publication on Instagram Hashtag Explorer. But the different citation standards provide guidelines for how to cite software, e.g. MLA:
			Rieder, Bernhard. Instagram Hashtag Explorer. Computer software. Vers. 1.1. N.p., 15 October 2015. Web. &lt;https://tools.digitalmethods.net/netvizz/instagram/&gt;.</p>
			
			
			<h2>I don't know how to use Instagram Hashtag Explorer, can you help me?</h2>
			
			<p>Unfortunately, I do not have the spare time to provide any assistance for this app and can therefore not respond to inquiries concerning how to use it or how
			to solve a particular research problem with it.</p>
			
			<p>There is an <a href="https://www.youtube.com/watch?v=o07aUKdRv0g" target="_blank">introductory video</a> and the interface 
			contains a description of what the tool does and further up you can find links to the relevant sections of the API. Most importantly, to make sense of
			the data, a good understanding of Instagram's basic architecture is required. The <a href="https://instagram.com/developer/" target="_blank">documentation</a>
			for Instagram's API has comprehensive descriptions of entities and metrics.</p>
			
			<p>If you would like to learn more about this kind of research, you may want to consider joining the Digital Methods Initiative's
			<a href="https://wiki.digitalmethods.net/Dmi/DmiSummerSchool" target="_blank">summer</a> or
			<a href="https://wiki.digitalmethods.net/Dmi/WinterSchool" target="_blank">winter</a> school, or even enrol in our M.A. program in
			<a href="http://studiegids.uva.nl/xmlpages/page/2014-2015-en/search-programme/programme/741" target="_blank">New Media and Digital Culture</a> or our two-year
			<a href="hhttp://studiegids.uva.nl/xmlpages/page/2014-2015-en/search-programme/programme/554" target="_blank">research MA</a>.
			In these programs, we combine training in analytical techniques with critical conceptual interrogation about new media.</p>
			
			
			<h2>How can I find lat/lng information for location search?</h2>
			
			<p><p>Check <a href="https://support.google.com/maps/answer/18539?hl=en" target="_blank">this page</a> for how to find lat/lng coordinates
			with Google Maps.</p>
			
						
			<h2>The tool does not work (correctly)!</h2>
	
			<p>While this is very simple software, this can happen for all kinds of reasons.</p>
	
			<p>High quality bug reports are much appreciated. If you have no experience with reporting bugs effectively, please read
			<a href="http://www.chiark.greenend.org.uk/~sgtatham/bugs.html" target="_blank">this piece</a> at least twice.
			TL;DR: developers need context to debug a tool, when filing a bug report, please add the URL of the call, the browser you are using, a
			screenshot of the interface output, the data files, and a description of what you have been doing and how the problem manifests itself. Without extensive
			information it can be very hard to replicate a problem and subsequently fix it.</p>
			
			<p>Please send bug reports to <a href="mailto:tools@polsys.net">tools@polsys.net</a>. I do not react to reports sent through any other channel.</p>
			
			
			<h2>Can you add feature X to Instagram Hashtag Explorer?</h2>
			
			<p>I cannot make any guarantees, but if you send a feature request to <a href="mailto:tools@polsys.net">tools@polsys.net</a>, I will definitely consider it.</p>
			
			
			<h2>Where is the source code?</h2>

			<p>The full source code is available on <a href="https://github.com/bernorieder/Instagram-Hashtag-Explorer/wiki" target="_blank">github</a>. You'll also find installation instructions there.</p>

		</td>
	</tr>
</table>

</body>
</html>