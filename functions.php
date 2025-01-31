<?php
/*
テーマのための関数
*/

/*#########################################################

基本設定

#########################################################*/
// WPのバージョンを非表示
remove_action('wp_head', 'wp_generator');

// WPのバージョン情報削除
function vc_remove_wp_ver_css_js($src) {
	if (strpos($src, 'ver=' . get_bloginfo('version')))
		$src = remove_query_arg('ver', $src);
	return $src;
}
add_filter('style_loader_src', 'vc_remove_wp_ver_css_js', 9999);
add_filter('script_loader_src', 'vc_remove_wp_ver_css_js', 9999);

// 絵文字削除
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

// フィードのlink要素を自動出力する
add_theme_support('automatic-feed-links');

//DNS Prefetch削除
remove_action('wp_head', 'wp_resource_hints', 2);

//Embed削除
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

//EditURI
remove_action('wp_head', 'rsd_link');

// RSSフィードのURLの削除
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

//wlwmanifest削除
remove_action('wp_head', 'wlwmanifest_link');

// ショートリンクURLの削除
remove_action('wp_head', 'wp_shortlink_wp_head');

// 投稿ページにてアイキャッチ画像の欄を表示
add_theme_support('post-thumbnails');

// WordPressコアから出力されるHTMLタグをHTML5のフォーマットにする
add_theme_support('html5', array(
	'search-form',
	'comment-form',
	'comment-list',
	'gallery',
	'caption',
));

// 投稿フォーマットのサポート
add_theme_support('post-formats', array(
	'aside',	//アサイド
	'gallery',	//ギャラリー
	'image',	//画像
	'link',		//リンク
	'quote',	//引用
	'status',	//ステータス
	'video',	//動画
	'audio',	//音声
	'chat',		//チャット
));

// 外観-メニューの表示
function register_my_menus() {
	register_nav_menus(array(
		'main-menu' => 'Main Menu',
		'tab-menu' => 'Responsive Menu',
		'footer-menu'  => 'Footer Menu',
	));
}
add_action('after_setup_theme', 'register_my_menus');

// メニューのクラスを消す
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
	// このクラスだけ残す
	return is_array($var) ? array_intersect($var, array('current-menu-item', '_contact', '_active')) : '';
}

// タイトルタグ
add_theme_support('title-tag');

// 固定ページにPHP読み込み
function my_php_Include($params = array()) {
	extract(shortcode_atts(array('file' => 'default'), $params));
	ob_start();
	include(STYLESHEETPATH . "/$file.php");
	return ob_get_clean();
}
add_shortcode('call_php', 'my_php_Include');

//固定ページで抜粋を使えるように
add_post_type_support('page', 'excerpt');

// スクリプト(jQuery)読み込み
wp_deregister_script('jquery');
wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), '3.6.0');

// SVGをアップロード
function add_file_types_to_uploads($file_types) {

	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes);

	return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');

// URLスラッグの自動生成
function auto_post_slug($slug, $post_ID, $post_status, $post_type) {
	if (preg_match('/(%[0-9a-f]{2})+/', $slug)) {
		$slug = utf8_uri_encode($post_type) . '-' . $post_ID;
	}
	return $slug;
}
add_filter('wp_unique_post_slug', 'auto_post_slug', 10, 4);

// 投稿のタグをチェックボックスで選択できるようにする
function change_post_tag_to_checkbox() {
	$args = get_taxonomy('post_tag');
	$args->hierarchical = true; //Gutenberg用
	$args->meta_box_cb = 'post_categories_meta_box'; //Classicエディタ用
	register_taxonomy('post_tag', 'post', $args);
}
add_action('init', 'change_post_tag_to_checkbox', 1);

/*****************************************
the_archive_title 余計な文字を削除
 *****************************************/
add_filter('get_the_archive_title', function ($title) {
	if (is_category()) {
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$title = single_tag_title('', false);
	} elseif (is_tax()) {
		$title = single_term_title('', false);
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	} elseif (is_date()) {
		$title = get_the_time('Y年n月');
	} elseif (is_search()) {
		$title = '検索結果：' . esc_html(get_search_query(false));
	} elseif (is_404()) {
		$title = '「404」ページが見つかりません';
	} else {
	}
	return $title;
});

/*****************************************
続きを読むリンク先を先頭から表示させる
 *****************************************/
function themename_modify_readmore() {
	return '<a href="' . get_permalink() . '" class="more-link">続きを読む</a>';
}
add_filter('the_content_more_link', 'themename_modify_readmore');

/*****************************************
CSS自動キャッシュ削除
 *****************************************/
function my_update_styles($styles) {
	$mtime = filemtime(get_stylesheet_directory() . '/style.css');
	$styles->default_version = $mtime;
}
add_action('wp_default_styles', 'my_update_styles');

/*****************************************
contactform7スパム対策（本文）
 *****************************************/
//-- Contact Form 7 の <textarea> にひらがなが含まれなければエラーにする
add_filter('wpcf7_validate_textarea', 'wpcf7_validation_textarea_hiragana', 10, 2);
add_filter('wpcf7_validate_textarea*', 'wpcf7_validation_textarea_hiragana', 10, 2);

function wpcf7_validation_textarea_hiragana($result, $tag) {
	$name = $tag['name'];
	$value = (isset($_POST[$name])) ? (string) $_POST[$name] : '';

	if ($value !== '' && !preg_match('/[ぁ-ん]/u', $value)) {
		$result['valid'] = false;
		$result['reason'] = array($name => 'この内容は送信できません。');
	}

	return $result;
}

/*#########################################################

汎用関数

#########################################################*/

// TITLE要素用
function my_wp_title($title) {

	if (is_front_page() && is_home()) {
		return get_bloginfo('name');
	} else {
		return $title . "|" . get_bloginfo('name');
	}
}
add_filter('wp_title', 'my_wp_title');

// 日付の出力
function smart_entry_date() {
	// 日付
	printf(
		'<time class="entry-date published" datetime="%1$s">%2$s</time>',
		esc_attr(get_the_date()),
		get_the_date()
	);
}

// カテゴリの出力
function smart_entry_category($pretag = "", $endtag = "") {
	$categories_list = get_the_category_list(', ');
	if ($categories_list) {
		printf(
			$pretag . '%1$s' . $endtag,
			$categories_list
		);
	}
}

// タグの出力
function smart_entry_tag($pretag = "", $endtag = "") {
	$tags_list = get_the_tag_list('', ', ');
	if ($tags_list) {
		printf(
			$pretag . '%1$s' . $endtag,
			$tags_list
		);
	}
}


/*#########################################################

テーマ専用処理

#########################################################*/

// 親ページのスラッグを取得する
function is_parent_slug($slug) {
	global $post;
	if (is_page()) {
		if ($post->post_parent) {
			$post_data = get_post($post->post_parent);
			if ($post_data->post_name === $slug) {
				return true;
			}
		}
		return false;
	}
}

// パンくずリスト
function get_breadcrumb_list($include_category = 1, $include_tag = 1, $include_taxonomy = 1) {
	// ベースパンくず(サイト名など)
	$base_breadcrumb = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . get_home_url() . '" itemprop="url"><span itemprop="title">ホーム</span></a></li>';
	$top_url = get_home_url(null, '/');
	// ループ外対応
	global $query_string;
	global $post;
	query_posts($query_string);
	if (have_posts()) : while (have_posts()) : the_post();
		endwhile;
	endif;
	// クエリリセットする
	wp_reset_query();
	// ページ数を取得(ページ数(0の場合は1ページ目なので1に変更する))
	if (get_query_var('paged') == 0) : $page = 1;
	else : $page = get_query_var('paged');
	endif;

	// 投稿・固定ページかつアタッチメントページ以外の場合
	if (is_singular() && !is_attachment()) {
		// 記事についているカテゴリを全て取得する
		$categories = get_the_category();
		// カテゴリがある場合に実行する
		if (!empty($categories)) {
			$category_count = count($categories);
			$loop = 1;
			$category_list = '';
			foreach ($categories as $category) {
				// 値が1だったら、URLにカテゴリーのタクソノミーを含めて変数に格納する。
				if ($include_category === 1) {
					$category_list .= '<a href="' . $top_url . esc_html($category->taxonomy) . '/' . esc_html($category->slug) . '/" itemprop="url"><span itemprop="title">' . esc_html($category->name) . '</span></a>';
				}
				// 値が指定されていないか1以外だったら、URLにカテゴリーのタクソノミーを含めないで変数に格納する。
				else {
					$category_list .= '<a href="' . $top_url . esc_html($category->slug) . '/" itemprop="url"><span itemprop="title">' . esc_html($category->name) . '</span></a>';
				}
				// ループの最後でない場合のみスラッシュ追加（複数カテゴリ対応）
				if ($loop != $category_count) {
					$category_list .= ' / ';
				}
				++$loop;
			}
		}
		// カテゴリがない場合はNULLを格納する
		else {
			$category_list = null;
		}
		// 記事についているタグを全て取得する
		$tags = get_the_tags();
		// タグがあれば実行する
		if (!empty($tags)) {
			$tags_count = count($tags);
			$loop = 1;
			$tag_list = '';
			foreach ($tags as $tag) {
				// 値が1だったら、URLにタグのタクソノミーを含めて変数に格納する。
				if ($include_tag === 1) {
					$tag_list .= '<a href="' . $top_url . esc_html($tag->taxonomy) . '/' . esc_html($tag->slug) . '/" itemprop="url"><span itemprop="title">' . esc_html($tag->name) . '</span></a>';
				}
				// 値が指定されていないか1以外だったら、URLにタグのタクソノミーを含めないで変数に格納する。
				else {
					$tag_list .= '<a href="' . $top_url . esc_html($tag->slug) . '/" itemprop="url"><span itemprop="title">' . esc_html($tag->name) . '</span></a>';
				}
				// ループの最後でない場合のみスラッシュ追加（複数タグ対応）
				if ($loop !== $tags_count) {
					$tag_list .= ' / ';
				}
				++$loop;
			}
		}
		// タグがない場合はNULLを格納する
		else {
			$tag_list = null;
		}
		// タクソノミーを全て取得する
		$taxonomies = get_the_taxonomies();
		// タクソノミーがある場合に実行する
		if (!empty($taxonomies)) {
			$term_list = '';
			$taxonomies_count = count($taxonomies);
			$taxonomies_loop = 1;
			foreach (array_keys($taxonomies) as $key) {
				// タームを取得
				$terms = get_the_terms($post->ID, $key);
				$terms_count = count($terms);
				$loop = 1;
				foreach ($terms as $term) {
					// 値が1だったら、URLにタクソノミーを含めて変数に格納する。
					if ($include_taxonomy === 1) {
						$term_list .= '<a href="' . $top_url . esc_html($term->taxonomy) . '/' . esc_html($term->slug) . '/" itemprop="url"><span itemprop="title">' . esc_html($term->name) . '</span></a>';
					}
					// 値が指定されていないか1以外だったらURLにタクソノミーを含めないで変数に格納する。
					else {
						$term_list .= '<a href="' . $top_url . esc_html($term->slug) . '/" itemprop="url"><span itemprop="title">' . esc_html($term->name) . '</span></a>';
					}
					// ループの最後でない場合のみスラッシュ追加（複数ターム対応）
					if ($loop != $terms_count) {
						$term_list .= ' / ';
					}
					++$loop;
				}
				// ループの最後でない場合のみスラッシュ追加（複数タクソノミー対応）
				if ($taxonomies_loop != $taxonomies_count) {
					$term_list .= ' / ';
				}
				++$taxonomies_loop;
			}
		}
		// タクソノミーがない場合はNULLを格納する
		else {
			$term_list = null;
		}
	}
	// 基本パンくずリストを変数に格納する。
	$breadcrumb_lists = $base_breadcrumb;
	// ホームの場合
	if (is_home()) {
		$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . 'お知らせ' . '</li>';
	}
	// カスタム投稿タイプアーカイブの場合
	elseif (is_post_type_archive()) {
		// ページ数が1より大きい場合(○ページ目)を追加して格納する
		if ($page > 1) {
			$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_post_type_object(get_post_type())->label) . '(' . $page . 'ページ目)</li>';
		}
		// ページ数が1の場合は(○ページ目)はなしで格納する
		else {
			$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_post_type_object(get_post_type())->label) . '</li>';
		}
	}
	// カスタム投稿タイプ以外のアーカイブの場合
	elseif (is_archive()) {
		// 年アーカイブの場合
		if (is_year()) {
			// ページ数が1より大きい場合(○ページ目)を追加して格納する
			if ($page > 1) {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_time("Y年")) . '(' . $page . 'ページ目)</li>';
			}
			// ページ数が1の場合は(○ページ目)はなしで格納する
			else {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_time("Y年")) . '</li>';
			}
		}
		// 月アーカイブの場合
		elseif (is_month()) {
			// ページ数が1より大きい場合(○ページ目)を追加して格納する
			if ($page > 1) {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_time("Y年m月")) . '(' . $page . 'ページ目)</li>';
			}
			// ページ数が1の場合は(○ページ目)はなしで格納する
			else {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_time("Y年m月")) . '</li>';
			}
		}
		// 日アーカイブの場合
		elseif (is_day()) {
			// ページ数が1より大きい場合(○ページ目)を追加して格納する
			if ($page > 1) {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_time("Y年m月d日")) . '(' . $page . 'ページ目)</li>';
			}
			// ページ数が1の場合は(○ページ目)はなしで格納する
			else {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_time("Y年m月d日")) . '</li>';
			}
		}
		// カテゴリの場合
		elseif (is_category()) {
			// ページ数が1より大きい場合(○ページ目)を追加して格納する
			if ($page > 1) {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="../../topics/">お知らせ</a></li><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(single_cat_title('', false)) . '(' . $page . 'ページ目)</li>';
			}
			// ページ数が1の場合は(○ページ目)はなしで格納する
			else {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="../../topics/">お知らせ</a></li><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(single_cat_title('', false)) . '</li>';
			}
		}
		// タグの場合
		elseif (is_tag()) {
			// ページ数が1より大きい場合(○ページ目)を追加して格納する
			if ($page > 1) {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(single_tag_title('', false)) . '(' . $page . 'ページ目)</li>';
			}
			// ページ数が1の場合は(○ページ目)はなしで格納する
			else {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(single_tag_title('', false)) . '</li>';
			}
		}
		// タクソノミー(カスタム分類)の場合
		elseif (is_tax()) {
			// ページ数が1より大きい場合(○ページ目)を追加して格納する
			if ($page > 1) {
				$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(single_term_title('', false)) . '(' . $page . 'ページ目)</li>';
			}
			// ページ数が1の場合は(○ページ目)はなしで格納する
			else {
				$term = get_queried_object(); // 現在のタームを取得
				$taxonomy = $term->taxonomy; // タームが所属するタクソノミーを取得
				$post_type = get_taxonomy($taxonomy)->object_type[0]; // タクソノミーが所属するカスタム投稿タイプを取得
				$post_type_object = get_post_type_object($post_type); // カスタム投稿タイプのオブジェクトを取得
				$post_type_name = $post_type_object->labels->singular_name; // カスタム投稿タイプの名称を取得
				$post_type_archive_link = get_post_type_archive_link($post_type); // カスタム投稿タイプのアーカイブリンクを取得

				$breadcrumb_lists .= '<li><a href="' . esc_url($post_type_archive_link) . '">' . $post_type_name . '</a></li><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(single_term_title('', false)) . '</li>';
			}
		}
	}
	// 投稿ページ
	elseif (is_single()) {
		// ページ数を取得(ページ数(0の場合は1ページ目なので1に変更する))
		if (get_query_var('page') == 0) : $page = 1;
		else : $page = get_query_var('page');
		endif;
		// 通常投稿の場合
		if (get_post_type() === 'post') {
			// カスタムフィールドの値を取得
			$seo_title = esc_html(get_post_meta($post->ID, 'seo_title', true));

			$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href=" ' . esc_url(home_url('/')) . 'topics/">お知らせ</a></li>';

			// カテゴリがある場合のみ追加
			// if (!empty($category_list)) {
			// 	$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . $category_list . '</li>';
			// }

			// 2ページ目以降の場合
			if ($page > 1) {
				// カスタムフィールドに値があったらその値を格納する
				if (!empty($seo_title)) {
					$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . $seo_title . '</li>';
				}
				// カスタムフィールドに値がなかった場合
				else {
					// ページが分割されている場合のタイトル取得
					if (function_exists('get_current_split_string')) {
						$split_title = esc_html(get_current_split_string($page));
					} else {
						$split_title = null;
					}
					// ページが分割されている場合のタイトルがあった場合はそれを加える
					if (!empty($split_title)) {
						$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_title()) . '【' . $split_title . '】</li>';
					}
					// それ以外は(○ページ目)を加える
					else {
						$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_title()) . '(' . $page . 'ページ目)</li>';
					}
				}
			}
			// 1ページ目の場合
			else {
				// カスタムフィールドに値があったらその値を格納する
				if (!empty($seo_title)) {
					$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . $seo_title . '</li>';
				}
				// カスタムフィールドに値がなかった場合
				else {
					$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_title()) . '</li>';
				}
			}
		}
		// カスタム投稿の場合
		else {
			// カスタム投稿名を取得
			$custom_title = esc_html(get_post_type_object(get_post_type())->label);
			// カスタム投稿のスラッグ名を取得
			$custom_name = esc_html(get_post_type_object(get_post_type())->name);
			// タームがある場合のみ追加
			if (!empty($term_list)) {
				// $breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . $term_list . '</li>';
			}
			$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . get_home_url() . '/' . $custom_name . '" itemprop="url">' . $custom_title . '</a></li><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' . esc_html(get_the_title()) . '</li>';
		}
	}
	// 固定ページ
	elseif (is_page()) {
		if (is_page() && $post->post_parent) { //子ページなら
			$parent_id = $post->post_parent;
			$parent_title = get_post($parent_id)->post_title;
			$parent_url = get_page_link($post->post_parent);
			$breadcrumb_lists .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . $parent_url . '"><span itemprop="name">' . $parent_title . '</span></a><meta itemprop="position" content="2" /><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html(get_the_title()) . '</span><meta itemprop="position" content="3" /></li>';
		} else {
			$breadcrumb_lists .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html(get_the_title()) . '</span><meta itemprop="position" content="2" /></li>';
		}
	}
	// 検索結果
	elseif (is_search()) {
		// ページ数が1より大きい場合(○ページ目)を追加して格納する
		if ($page > 1) {
			$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">「' . esc_html(get_search_query()) . '」で検索した結果(' . $page . 'ページ目)</li>';
		}
		// ページ数が1の場合は(○ページ目)はなしで格納する
		else {
			$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">「' . esc_html(get_search_query()) . '」で検索した結果</li>';
		}
	}
	// 404ページの場合
	elseif (is_404()) {
		$breadcrumb_lists .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">お探しのページは見つかりませんでした</li>';
	} else {
		$breadcrumb_lists = $base_breadcrumb;
	}
	// パンくずリスト成形
	if (!empty($breadcrumb_lists)) {
		$breadcrumb_lists = '<ul class="breadcrumb__list">' . $breadcrumb_lists . '</ul>';
	}
	return $breadcrumb_lists;
}

// カスタム投稿の編集画面に「投稿者」を表示する
add_action('admin_menu', 'myplugin_add_custom_box');
function myplugin_add_custom_box() {
	if (function_exists('add_meta_box')) {
		add_meta_box('myplugin_sectionid', __('投稿者', 'myplugin_textdomain'), 'post_author_meta_box', array('blog', 'voice'), 'advanced');
	}
}
function manage_blog_columns($columns) {
	$columns['author'] = '投稿者';
	return $columns;
}
function add_blog_column($column, $post_id) {
	if ('author' == $column) {
		$value = get_the_term_list($post_id, 'author');
		echo attribute_escape($value);
	}
}
add_filter('manage_posts_columns', 'manage_blog_columns');
add_action('manage_posts_custom_column', 'add_blog_column', 10, 2);
