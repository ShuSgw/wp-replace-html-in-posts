<?php
/*
Plugin Name: Replace Post Data with HTML 
Description: ページのコンテンツをカスタムHTMLで置き換えるプラグイン。theme直下にlocalというディレクトリを作成してページのslugで名前をつけて、もし親ページがある場合はそのslugをディレクトリ名として指定してその中にその親を持つhtmlを入れてください。例：theme/local/test.html/parents/child.html
*/

// アクティベート時に実行する処理
register_activation_hook(__FILE__, 'custom_content_activate');
function custom_content_activate()
{
    // すべてのページのコンテンツを指定したHTMLで置き換える
    update_option('custom_content_active', true);
}

// 無効化時に実行する処理
register_deactivation_hook(__FILE__, 'custom_content_deactivate');
function custom_content_deactivate()
{
    // カスタムコンテンツの置き換えを解除
    update_option('custom_content_active', false);
}

// コンテンツフィルターを追加
add_filter('the_content', 'custom_content_filter');
function custom_content_filter($content)
{
    // カスタムコンテンツがアクティブかどうかを確認
    $custom_content_active = get_option('custom_content_active');

    if ($custom_content_active) {
        // 現在のページのslugを取得
        global $post;
        $current_slug = $post->post_name;

        $parent_slug = '';
        // HTMLファイルが格納されているディレクトリのパス
        $html_directory = get_template_directory() . '/local/';

        if ($post->post_parent) {
            $parent_post = get_post($post->post_parent);
            $parent_slug = $parent_post->post_name;

            $html_directory = get_template_directory() . '/local/' . $parent_slug . '/';
        }

        // ディレクトリ内のすべてのHTMLファイルを取得
        $html_files = scandir($html_directory);

        // ディレクトリ内のHTMLファイルを順番に検索
        foreach ($html_files as $html_file) {
            if ($html_file === '.' || $html_file === '..') {
                continue;
            }

            $file_slug = pathinfo($html_file, PATHINFO_FILENAME);

            if ($current_slug === $file_slug) {

                $plugin_folder_path = plugin_dir_url(__FILE__);
                wp_enqueue_style('custom-plugin-style', $plugin_folder_path . 'style.css');

                // カスタムコンテンツを読み込む前にHTML読み込み中の要素を追加
                $loading_html = '<div class="post-replacer-warning">HTML読み込み中</div>';
                $html_file_path = $html_directory . $html_file;
                $content = $loading_html . do_shortcode(file_get_contents($html_file_path));
                break; // 一致するファイルが見つかればループを終了
            } else {
                $content;
            }
        }
    }
    return $content;
}
