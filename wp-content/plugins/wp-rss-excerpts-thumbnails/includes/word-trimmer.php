<?php

/**
 * Contains the word trimming function used to trim excerpts.
 *
 * @since 1.8
 * @package WP RSS Aggregator
 * @subpackage Excerpts & Thumbnails
 */


/**
 * Trims the given text by a fixed number of words, and preserving HTML.
 *
 * Collapses all white space, trims the text up to a certain number of words, and
 * preserves all HTML markup. HTML tags do not count as words.
 * Uses WordPress `wp_trim_words` internally.
 * Uses mostly trivial regex. Works by removing, then re-adding tags.
 * Just as well closes open tags by counting them.
 * 
 * @since 1.8
 * @see wprss_trim_words()
 * @param string $text The text to trim.
 * @param string $max_words The maximum number of words.
 * @param array $allowed_tags The allows tags. Regular array of tag names.
 * @return string The trimmed text.
 */
function wprss_et_trim_words( $text, $max_words, $allowed_tags = array() ) {
	// Use core's function if it exists.
	if ( function_exists( 'wprss_trim_words' ) ) {
		return wprss_trim_words( $text, $max_words, $allowed_tags );
	}
	
	// See http://haacked.com/archive/2004/10/25/usingregularexpressionstomatchhtml.aspx/
	$html_regex = <<<EOS
(</?(\w+)(?:(?:\s+\w+(?:\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)/?>)
EOS;
	$html_regex_str = sprintf ('!%1$s!', $html_regex );
	// Collapsing single-line white space
	$text = preg_replace( '!\s+!', ' ', $text );

	// Enum of tag types
	$tag_type = array(
		'opening'		=> 1,
		'closing'		=> 2,
		'self-closing'	=> 0
	);
	
	/*
	 * Split text using tags as delimiters.
	 * The resulting array is a sequence of elements as follows:
	 * 	0 - The complete tag that it was delimited by
	 * 	1 - The name of that tag
	 * 	2 - The text that follows it until the next tag
	 * 
	 * Each element contains 2 indexes:
	 * 	0 - The element content
	 * 	1 - The position in the original string, at which it was found
	 *
	 * For instance:
	 *		<span>hello</span> how do <em>you do</em>?
	 *
	 * Will result in an array (not actaul structure) containing:
	 * <span>, span, hello, </span>, span, how do, <em>, em, you do, </em>, em, ?
	 */
	$text_array = preg_split(
		$html_regex_str,				// Match HTML Regex above
		$text,							// Split the text
		-1,								// No split limit
		// FLAGS
			PREG_SPLIT_DELIM_CAPTURE	// Capture delimiters (html tags)
		|	PREG_SPLIT_OFFSET_CAPTURE	// Record the string offset of each part
	);
	/*
	 * Get first element of the array (leading text with no HTML), and add it to a string.
	 * This string will contain the plain text (no HTML) only after the follow foreach loop.
	 */
	$text_start = array_shift( $text_array );
	$plain_text = $text_start[0];

	/*
	 * Chunk the array in groups of 3. This will take each 3 consecutive elements
	 * and group them together.
	 */
	$pieces = array_chunk( $text_array, 3 );


	/*
	 * Iterate over each group and:
	 *	1. Generate plain text without HTML
	 *	2. Add apropriate tag type to each group
	 */
	foreach ( $pieces as $_idx => $_piece ) {
		// Get the data
		$tag_piece = $_piece[0];
		$text_piece = $_piece[2];
		// Compile all plain text together
		$plain_text .= $text_piece[0];
		// Check the tag and assign the proper tag type
		$tag = $tag_piece[0];
		$pieces[ $_idx ][1][2] = 
			( substr( $tag, 0, 2 ) === '</' )?
				$tag_type['closing'] :
			( substr( $tag, strlen( $tag ) - 3, 2 ) == '/>' )?
				$tag_type['self-closing'] :
				$tag_type['opening'];
	}

	// Stock trimming of words
	$plain_text = wp_trim_words_et( $plain_text, $max_words );

	/*
	 * Put the tags back, using the offsets recorded
	 * This is where the sweet magic happens
	 */

	// Cache to only check `in_array` once for each tag type
	$allowed_tags_cache = array();
	// For counting open tags
	$tags_to_close = array();
	// Since some tags will not be included...
	$tag_position_offset = 0;
	$text = $plain_text;

	// Iterate the groups once more
	foreach ( $pieces as $_idx => $_piece ) {
		// Tag and tagname
		$_tag_piece = $_piece[0];
		$_tag_name_piece = $_piece[1];
		// Name of the tag
		$_tag_name = strtolower( $_tag_name_piece[0] );
		// Tag type
		$_tag_type = $_tag_name_piece[2];
		// Text of the tag
		$_tag = $_tag_piece[0];
		// Position of the tag in the original string
		$_tag_position = $_tag_piece[1];
		$_actual_tag_position = $_tag_position - $tag_position_offset;

		// Caching result
		if ( !isset( $allowed_tags_cache[$_tag_name] ) )
			$allowed_tags_cache[$_tag_name] = in_array( $_tag_name, $allowed_tags );

		// Whether to stop (tag position is outside the trimmed text)
		if( $_actual_tag_position >= strlen( $text ) ) break;

		// Whether to skip tag
		if ( !$allowed_tags_cache[$_tag_name] ) {
			$tag_position_offset += strlen( $_tag ); // To correct for removed chars
			continue;
		}

		// If the tag is an opening tag, record it in $tags_to_close
		if( $_tag_type === $tag_type['opening'] )
			array_push( $tags_to_close, $_tag_name );
		// If it is a closing tag, remove it from $tags_to_close
		elseif( $_tag_type === $tag_type['closing'] )
			array_pop( $tags_to_close );

		// Inserting tag back into place
		$text = substr_replace( $text, $_tag, $_actual_tag_position, 0);
	}

	// Add the appropriate closing tags to all unclosed tags
	foreach( $tags_to_close as $_tag_name ) {
		$text .= sprintf('</%1$s>', $_tag_name);
	}
	
	return $text;
}


/**
 * Clone of wp_trim_words, without using the PREG_SPLIT_NO_EMPTY flag for preg_split
 * 
 * Trims text to a certain number of words.
 * This function is localized. For languages that count 'words' by the individual
 * character (such as East Asian languages), the $num_words argument will apply
 * to the number of individual characters.
 *
 * @since 1.8
 *
 * @param string $text Text to trim.
 * @param int $num_words Number of words. Default 55.
 * @param string $more Optional. What to append if $text needs to be trimmed. Default '&hellip;'.
 * @return string Trimmed text.
 */
function wp_trim_words_et( $text, $num_words = 55, $more = null ) {
	if ( null === $more ) {
		$more = __( '&hellip;' );
	}
	$original_text = $text;
	/* translators: If your word count is based on single characters (East Asian characters),
	   enter 'characters'. Otherwise, enter 'words'. Do not translate into your own language. */
	if ( 'characters' == _x( 'words', 'word count: words or characters?' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep = '';
	} else {
		$words_array = preg_split( "/[\n\r\t ]/", $text, $num_words + 1 );
		$sep = ' ';
	}
	if ( count( $words_array ) > $num_words ) {
		array_pop( $words_array );
		$text = implode( $sep, $words_array );
		$text = $text . $more;
	} else {
		$text = implode( $sep, $words_array );
	}
	/**
	 * Filter the text content after words have been trimmed.
	 *
	 * @since 3.3.0
	 *
	 * @param string $text          The trimmed text.
	 * @param int    $num_words     The number of words to trim the text to. Default 5.
	 * @param string $more          An optional string to append to the end of the trimmed text, e.g. &hellip;.
	 * @param string $original_text The text before it was trimmed.
	 */
	return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
}