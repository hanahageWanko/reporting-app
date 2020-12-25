# プロジェクトごとの学習時間計算アプリ

## 概要
- プロジェクトごとの学習記録をユーザー単位で記録し、閲覧やデータの集計を行うことができる学習時間の記録アプリ

## ユースケース
- ユーザー登録及び学習時間の閲覧/登録を行う。
- プロジェクトのカテゴリごとに学習時間を登録することにより、日々の平均学習時間や学習合計時間を確認する。

## 環境
|||
|--|--|
| 言語 | Frontend：HTML, SCSS, Javascript(Vue.js 3.0.0) 予定 <br>Backend：PHP 7.3.11<br>※フロンドエンドは現状未実装|
| DBMS | MySQL 8.0.21 |
| フレームワーク | 未使用 |
| 開発環境 | MacOS Catalina 10.15.7 |
| バージョン管理 | Git 2.28.0 |
| 本番環境 | AWS予定 |

## API一覧
### プロジェクト管理
|URL|機能概要|
|--|--|
|project_category/read.php| 登録プロジェクトの一覧取得|
|project_category/read.php?id=(number)| 任意IDのプロジェク情報取得|
|project_category/insert.php| プロジェクト登録|
|project_category/update.php| プロジェクトの登録内容を更新|
|project_category/delete.php| プロジェクトを削除|

### ユーザー管理
|URL|機能概要|
|--|--|
|users/read.php| 登録ユーザーの一覧取得|
|users/read.php?id=(number)| 任意IDのユーザー情報取得|
|project_category/insert.php| ユーザー登録|
|project_category/update.php| ユーザーの登録内容を更新|
|project_category/delete.php| ユーザーを削除 & ユーザーに紐ついている学習詳細も削除|

### 学習記録管理
|URL|機能概要|
|--|--|
|study_detail/read.php| 登録学習記録の一覧取得(紐づいているユーザー名/プロジェクト名含む)|
|study_detail/read.php?user_id=(number)| 任意ユーザーの学習記録一覧(紐づいているユーザー名/プロジェクト名含む)を取得|
|study_detail/insert.php| 学習記録登録|
|study_detail/update.php| 学習記録内容を更新|
|study_detail/delete.php| 学習記録内容を削除|