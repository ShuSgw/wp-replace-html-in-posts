### POST to HTML Overwriter

プラグインがアクティベートされてるときにだけ、優先的にローカル側の HTML を WordPress の the_content として呼び出します。

#### 使い方

- テーマディレクトリの中の root(base 直下、theme/base/ここ)に local というファイルを作る
- その中に html ファイルを作る（例：sample.html）
- html のファイル名は表示したいページの slug と紐づけてください（例：website.com/sample）
- するとプラグインをオンにしてる間この html が WordPress のエディタに記載した部分として仮で出てきます

#### 注意

- 親を持つページを作成する場合はこの html が入ってるフォルダ（local の中）に親 slug と同じ名前でディレクトリを作ってそこに html を入れてください。
- WordPress の投稿内容を仮想的に上書きしてるだけなため手動にてエディタに書き込む作業は必要です（それができるプラグインも今作ってます、これと書き込みプラグインが合体できたらと思います）
- 特殊ページ（フロントページやアーカイブページ等）はまだ対応してません

例：

http\://sample/about/company

theme/base/local/about/company

http\://sample/company

theme/base/local/company

とすることで同じ slug でも別物と判断されるようになります。（２階層までしか対応してません）

バグレポートお待ちしてます。
