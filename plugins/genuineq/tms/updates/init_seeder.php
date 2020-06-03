<?php namespace Genuineq\Tms\Updates;

use October\Rain\Database\Updates\Seeder;

class InitSeeder extends Seeder
{
    /**
     * Populates system tables and dependency plugins tables.
     */
    public function run()
    {
        /** Populate system_settings table. */
        Db::insert(
            'INSERT INTO system_settings (item, value) values (?, ?)',
            [
                ['backend_brand_settings',           '{\"app_name\":\"Scoala Profesorilor\",\"app_tagline\":\"Scoala Profesorilor\",\"primary_color\":\"#4c025e\",\"secondary_color\":\"#00d857\",\"accent_color\":\"#f1f1f1\",\"menu_mode\":\"inline\",\"custom_css\":\"\"}'],
                ['backend_editor_settings',          '{\"html_allow_empty_tags\":\"textarea, a, iframe, object, video, style, script\",\"html_allow_tags\":\"a, abbr, address, area, article, aside, audio, b, base, bdi, bdo, blockquote, br, button, canvas, caption, cite, code, col, colgroup, datalist, dd, del, details, dfn, dialog, div, dl, dt, em, embed, fieldset, figcaption, figure, footer, form, h1, h2, h3, h4, h5, h6, header, hgroup, hr, i, iframe, img, input, ins, kbd, keygen, label, legend, li, link, main, map, mark, menu, menuitem, meter, nav, noscript, object, ol, optgroup, option, output, p, param, pre, progress, queue, rp, rt, ruby, s, samp, script, style, section, select, small, source, span, strike, strong, sub, summary, sup, table, tbody, td, textarea, tfoot, th, thead, time, title, tr, track, u, ul, var, video, wbr\",\"html_no_wrap_tags\":\"figure, script, style\",\"html_remove_tags\":\"script, style\",\"html_line_breaker_tags\":\"figure, table, hr, iframe, form, dl\",\"html_custom_styles\":\"\\/*\\r\\n * Text\\r\\n *\\/\\r\\n.oc-text-gray {\\r\\n    color: #AAA !important;\\r\\n}\\r\\n.oc-text-bordered {\\r\\n    border-top: solid 1px #222;\\r\\n    border-bottom: solid 1px #222;\\r\\n    padding: 10px 0;\\r\\n}\\r\\n.oc-text-spaced {\\r\\n    letter-spacing: 1px;\\r\\n}\\r\\n.oc-text-uppercase {\\r\\n    text-transform: uppercase;\\r\\n}\\r\\n\\r\\n\\/*\\r\\n * Links\\r\\n *\\/\\r\\na.oc-link-strong {\\r\\n    font-weight: 700;\\r\\n}\\r\\na.oc-link-green {\\r\\n    color: green;\\r\\n}\\r\\n\\r\\n\\/*\\r\\n * Table\\r\\n *\\/\\r\\ntable.oc-dashed-borders td,\\r\\ntable.oc-dashed-borders th {\\r\\n    border-style: dashed;\\r\\n}\\r\\ntable.oc-alternate-rows tbody tr:nth-child(2n) {\\r\\n    background: #f5f5f5;\\r\\n}\\r\\n\\r\\n\\/*\\r\\n * Table cell\\r\\n *\\/\\r\\ntable td.oc-cell-highlighted,\\r\\ntable th.oc-cell-highlighted {\\r\\n    border: 1px double red;\\r\\n}\\r\\ntable td.oc-cell-thick-border,\\r\\ntable th.oc-cell-thick-border {\\r\\n    border-width: 2px;\\r\\n}\\r\\n\\r\\n\\/*\\r\\n * Images\\r\\n *\\/\\r\\nimg.oc-img-rounded {\\r\\n    border-radius: 100%;\\r\\n    background-clip: padding-box;\\r\\n}\\r\\nimg.oc-img-bordered {\\r\\n    border: solid 10px #CCC;\\r\\n    box-sizing: content-box;\\r\\n}\",\"html_style_image\":[{\"class_label\":\"Rounded\",\"class_name\":\"oc-img-rounded\"},{\"class_label\":\"Bordered\",\"class_name\":\"oc-img-bordered\"}],\"html_style_link\":[{\"class_label\":\"Green\",\"class_name\":\"oc-link-green\"},{\"class_label\":\"Strong\",\"class_name\":\"oc-link-strong\"}],\"html_style_paragraph\":[{\"class_label\":\"Bordered\",\"class_name\":\"oc-text-bordered\"},{\"class_label\":\"Gray\",\"class_name\":\"oc-text-gray\"},{\"class_label\":\"Spaced\",\"class_name\":\"oc-text-spaced\"},{\"class_label\":\"Uppercase\",\"class_name\":\"oc-text-uppercase\"}],\"html_style_table\":[{\"class_label\":\"Dashed Borders\",\"class_name\":\"oc-dashed-borders\"},{\"class_label\":\"Alternate Rows\",\"class_name\":\"oc-alternate-rows\"}],\"html_style_table_cell\":[{\"class_label\":\"Highlighted\",\"class_name\":\"oc-cell-highlighted\"},{\"class_label\":\"Thick Border\",\"class_name\":\"oc-cell-thick-border\"}],\"html_toolbar_buttons\":\"\"}'],
                ['rainlab_builder_settings',         '{\"author_name\":\"genuineq\",\"author_namespace\":\"Genuineq\"}'],
                ['user_settings',                    '{\"require_activation\":\"0\",\"activate_mode\":\"user\",\"use_throttle\":\"1\",\"block_persistence\":\"0\",\"allow_registration\":\"1\",\"login_attribute\":\"email\",\"remember_login\":\"never\",\"min_password_length\":8,\"use_register_throttle\":\"1\"}'],
                ['offline_sitesearch_settings',      '{\"mark_results\":\"1\",\"log_queries\":\"0\",\"excerpt_length\":\"250\",\"log_keep_days\":365,\"rainlab_blog_enabled\":\"0\",\"rainlab_blog_label\":\"Blog\",\"rainlab_blog_page\":\"blog\",\"rainlab_pages_enabled\":\"0\",\"rainlab_pages_label\":\"Page\",\"indikator_news_enabled\":\"0\",\"indikator_news_label\":\"News\",\"indikator_news_posturl\":\"\\/news\",\"octoshop_products_enabled\":\"0\",\"octoshop_products_label\":\"\",\"octoshop_products_itemurl\":\"\\/product\",\"snipcartshop_products_enabled\":\"0\",\"snipcartshop_products_label\":\"\",\"jiri_jkshop_enabled\":\"0\",\"jiri_jkshop_label\":\"\",\"jiri_jkshop_itemurl\":\"\\/product\",\"radiantweb_problog_enabled\":\"0\",\"radiantweb_problog_label\":\"Blog\",\"arrizalamin_portfolio_enabled\":\"0\",\"arrizalamin_portfolio_label\":\"Portfolio\",\"arrizalamin_portfolio_url\":\"\\/portfolio\\/project\",\"vojtasvoboda_brands_enabled\":\"0\",\"vojtasvoboda_brands_label\":\"Brands\",\"vojtasvoboda_brands_url\":\"\\/brand\",\"responsiv_showcase_enabled\":\"0\",\"responsiv_showcase_label\":\"Showcase\",\"responsiv_showcase_url\":\"\\/showcase\\/project\",\"graker_photoalbums_enabled\":\"0\",\"graker_photoalbums_label\":\"PhotoAlbums\",\"graker_photoalbums_album_page\":\"blog\",\"graker_photoalbums_photo_page\":\"blog\",\"cms_pages_enabled\":\"0\",\"cms_pages_label\":\"Page\"}'],
                ['ginopane_awesomesociallinks',      '{\"links\":[{\"icon\":\"fab fa-facebook-f\",\"name\":\"facebook\",\"link\":\"https:\\/\\/www.facebook.com\\/%C8%98coala-Profesorilor-100101075067282\\/\"},{\"icon\":\"fab fa-linkedin-in\",\"name\":\"linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/company\\/%C8%99coala-profesorilor\\/\"},{\"icon\":\"fab fa-instagram\",\"name\":\"instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/scoala_profesorilor\\/\"}]}'],
                ['system_log_settings',              '{\"log_events\":\"1\",\"log_requests\":\"1\",\"log_theme\":\"1\"}']
            ]
        );

        /** Populate system_mail_templates table. */
        Db::insert(
            'INSERT INTO system_mail_templates (id, code, subject, description, content_html, content_text, layout_id, is_custom, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                [1,  'genuineq.user::mail.activate',                 NULL, 'Activate a new user',                NULL, NULL, 1, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [2,  'genuineq.user::mail.welcome',                  NULL, 'User confirmed their account',       NULL, NULL, 1, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [3,  'genuineq.user::mail.restore',                  NULL, 'User requests a password reset',     NULL, NULL, 1, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [4,  'genuineq.user::mail.new_user',                 NULL, 'Notify admins of a new sign up',     NULL, NULL, 2, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [5,  'genuineq.user::mail.reactivate',               NULL, 'User has reactivated their account', NULL, NULL, 1, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [6,  'genuineq.user::mail.invite',                   NULL, 'Invite a new user to the website',   NULL, NULL, 1, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [7,  'backend::mail.invite',                         NULL, 'Invite new admin to the site',       NULL, NULL, 2, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [8,  'backend::mail.restore',                        NULL, 'Reset an admin password',            NULL, NULL, 2, 0, '2020-05-14 08:43:32', '2020-05-14 08:43:32'],
                [9,  'genuineq.tms::mail.teacher-course-request',    NULL, 'Noua cerere de curs',                NULL, NULL, 1, 0, '2020-05-28 23:02:45', '2020-05-28 23:02:45'],
                [10, 'genuineq.tms::mail.teacher-course-approve',    NULL, 'Noua cerere de curs',                NULL, NULL, 1, 0, '2020-05-28 23:02:45', '2020-05-28 23:02:45'],
                [11, 'genuineq.tms::mail.teacher-course-reject',     NULL, 'Noua cerere de curs',                NULL, NULL, 1, 0, '2020-05-28 23:02:45', '2020-05-28 23:02:45'],
                [12, 'genuineq.tms::mail.school-course-request',     NULL, 'Noua propunere de curs',             NULL, NULL, 1, 0, '2020-05-29 23:56:15', '2020-05-29 23:56:15'],
                [13, 'genuineq.tms::mail.school-course-approve',     NULL, 'Cerere de curs aprobata',            NULL, NULL, 1, 0, '2020-05-29 23:56:15', '2020-05-29 23:56:15'],
                [14, 'genuineq.tms::mail.school-course-reject',      NULL, 'Cerere de curs respinsa',            NULL, NULL, 1, 0, '2020-05-29 23:56:15', '2020-05-29 23:56:15'],
                [15, 'genuineq.tms::mail.teacher-objectives-set',    NULL, 'Obiective setate',                   NULL, NULL, 1, 0, '2020-05-30 01:11:32', '2020-05-30 01:11:32'],
                [16, 'genuineq.tms::mail.school-objectives-approve', NULL, 'Obiective aprobate',                 NULL, NULL, 1, 0, '2020-05-30 01:11:32', '2020-05-30 01:11:32'],
                [17, 'genuineq.tms::mail.school-skills-set',         NULL, 'Skill-uri setate',                   NULL, NULL, 1, 0, '2020-05-30 01:11:32', '2020-05-30 01:11:32'],
                [18, 'genuineq.tms::mail.school-evaluation-close',   NULL, 'Evaluare finalizata',                NULL, NULL, 1, 0, '2020-05-30 01:11:32', '2020-05-30 01:11:32']
            ]
        );

        /** Populate system_mail_partials table. */
        Db::insert(
            'INSERT INTO system_mail_partials (id, name, code, content_html, content_text, is_custom, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                [
                    1,
                    'Header',
                    'header',
                    '<tr>\n    <td class=\"header\">\n        {% if url %}\n            <a href=\"{{ url }}\">\n                {{ body }}\n            </a>\n        {% else %}\n            <span>\n                {{ body }}\n            </span>\n        {% endif %}\n    </td>\n</tr>',
                    '*** {{ body|trim }} <{{ url }}>',
                    0,
                    '2020-05-14 08:43:32',
                    '2020-05-14 08:43:32'
                ],
                [
                    2,
                    'Footer',
                    'footer',
                    '<tr>\n    <td>\n        <table class=\"footer\" align=\"center\" width=\"570\" cellpadding=\"0\" cellspacing=\"0\">\n            <tr>\n                <td class=\"content-cell\" align=\"center\">\n                    {{ body|md_safe }}\n                </td>\n            </tr>\n        </table>\n    </td>\n</tr>',
                    '-------------------\n{{ body|trim }}',
                    0,
                    '2020-05-14 08:43:32',
                    '2020-05-14 08:43:32'
                ],
                [
                    3,
                    'Button',
                    'button',
                    '<table class=\"action\" align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td align=\"center\">\n            <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n                <tr>\n                    <td align=\"center\">\n                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n                            <tr>\n                                <td>\n                                    <a href=\"{{ url }}\" class=\"button button-{{ type ?: \'primary\' }}\" target=\"_blank\">\n                                        {{ body }}\n                                    </a>\n                                </td>\n                            </tr>\n                        </table>\n                    </td>\n                </tr>\n            </table>\n        </td>\n    </tr>\n</table>',
                    '{{ body|trim }} <{{ url }}>',
                    0,
                    '2020-05-14 08:43:32',
                    '2020-05-14 08:43:32'
                ],
                [
                    4,
                    'Panel',
                    'panel',
                    '<table class=\"panel break-all\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td class=\"panel-content\">\n            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n                <tr>\n                    <td class=\"panel-item\">\n                        {{ body|md_safe }}\n                    </td>\n                </tr>\n            </table>\n        </td>\n    </tr>\n</table>',
                    '{{ body|trim }}',
                    0,
                    '2020-05-14 08:43:32',
                    '2020-05-14 08:43:32'],
                [
                    5,
                    'Table',
                    'table',
                    '<div class=\"table\">\n    {{ body|md_safe }}\n</div>',
                    '{{ body|trim }}',
                    0,
                    '2020-05-14 08:43:32',
                    '2020-05-14 08:43:32'
                ],
                [
                    6,
                    'Subcopy',
                    'subcopy',
                    '<table class=\"subcopy\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td>\n            {{ body|md_safe }}\n        </td>\n    </tr>\n</table>',
                    '-----\n{{ body|trim }}',
                    0,
                    '2020-05-14 08:43:32',
                    '2020-05-14 08:43:32'
                ],
                [
                    7,
                    'Promotion',
                    'promotion',
                    '<table class=\"promotion break-all\" align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td align=\"center\">\n            {{ body|md_safe }}\n        </td>\n    </tr>\n</table>',
                    '{{ body|trim }}',
                    0,
                    '2020-05-14 08:43:32',
                    '2020-05-14 08:43:32'
                ],
                [
                    8,
                    'Buttons',
                    'buttons',
                    '<table class=\"action\" align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tr>\r\n        <td align=\"center\">\r\n            <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tr>\r\n                    <td align=\"center\">\r\n                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n                            <tr>\r\n                                <td>\r\n                                    <a href=\"{{ url_reject }}\" class=\"button button-negative\" target=\"_blank\">\r\n                                        Respinge\r\n                                    </a>\r\n                                </td>\r\n                            </tr>\r\n                        </table>\r\n                    </td>\r\n                    <td align=\"center\">\r\n                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n                            <tr>\r\n                                <td>\r\n                                    <a href=\"{{ url_accept }}\" class=\"button button-positive\" target=\"_blank\">\r\n                                        Accepta\r\n                                    </a>\r\n                                </td>\r\n                            </tr>\r\n                        </table>\r\n                    </td>\r\n                </tr>\r\n            </table>\r\n        </td>\r\n    </tr>\r\n</table>',
                    '',
                    1,
                    '2020-05-28 22:45:25',
                    '2020-05-28 23:23:37'
                ]
            ]
        );

        /** Populate deferred_bindings table. */
        Db::insert(
            'INSERT INTO deferred_bindings (id, master_type, master_field, slave_type, slave_id, session_key, is_bind, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                [1,  'RainLab\\Notify\\Models\\RuleCondition'   , 'children',        'RainLab\\Notify\\Models\\RuleCondition', '2',  'o3qsHu4ZvPVRiKoPVBOIf3J7b4V2Mam5nOSl3hnc_1', 1, '2020-05-28 13:10:53', '2020-05-28 13:10:53'],
                [2,  'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '3',  '1yC0FmmjIknobIj0LsLzA8SQPWxEIWxL453eUetH',   1, '2020-05-28 13:45:23', '2020-05-28 13:45:23'],
                [3,  'RainLab\\Notify\\Models\\NotificationRule', 'rule_conditions', 'RainLab\\Notify\\Models\\RuleCondition', '3',  'ZdBddJI7gh7DdG9KMVy6vkUEf8p1hnNd7VNg6uaH',   1, '2020-05-28 17:40:02', '2020-05-28 17:40:02'],
                [4,  'RainLab\\Notify\\Models\\RuleCondition'   , 'children',        'RainLab\\Notify\\Models\\RuleCondition', '4',  'ZdBddJI7gh7DdG9KMVy6vkUEf8p1hnNd7VNg6uaH_3', 1, '2020-05-28 17:40:47', '2020-05-28 17:40:47'],
                [5,  'RainLab\\Notify\\Models\\NotificationRule', 'rule_conditions', 'RainLab\\Notify\\Models\\RuleCondition', '5',  'NCy0z9A6ciyo60dRqfM2y6IwotIzcPaUkPKb10Pz',   1, '2020-05-28 19:34:41', '2020-05-28 19:34:41'],
                [6,  'RainLab\\Notify\\Models\\NotificationRule', 'rule_conditions', 'RainLab\\Notify\\Models\\RuleCondition', '6',  'XJJNLOLx79X6XCWpbxXzURr5nOW83LPNPbO80EnM',   1, '2020-05-28 19:45:26', '2020-05-28 19:45:26'],
                [7,  'RainLab\\Notify\\Models\\NotificationRule', 'rule_conditions', 'RainLab\\Notify\\Models\\RuleCondition', '7',  'AJVGgwQ7kXF551bTqJwbq8xSl3piixesA27DVy0H',   1, '2020-05-28 21:55:35', '2020-05-28 21:55:35'],
                [8,  'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '6',  'AJVGgwQ7kXF551bTqJwbq8xSl3piixesA27DVy0H',   1, '2020-05-28 21:55:46', '2020-05-28 21:55:46'],
                [9,  'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '7',  'AJVGgwQ7kXF551bTqJwbq8xSl3piixesA27DVy0H',   1, '2020-05-28 21:59:37', '2020-05-28 21:59:37'],
                [10, 'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '8',  'AJVGgwQ7kXF551bTqJwbq8xSl3piixesA27DVy0H',   1, '2020-05-28 22:08:11', '2020-05-28 22:08:11'],
                [11, 'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '9',  'AJVGgwQ7kXF551bTqJwbq8xSl3piixesA27DVy0H',   1, '2020-05-28 22:17:09', '2020-05-28 22:17:09'],
                [12, 'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '10', 'AJVGgwQ7kXF551bTqJwbq8xSl3piixesA27DVy0H',   1, '2020-05-28 22:17:54', '2020-05-28 22:17:54'],
                [13, 'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '11', 'NyMb2uouTVOJGfaczodEQXmPzOdpfAh0ICUuV4nt',   1, '2020-05-28 22:54:47', '2020-05-28 22:54:47'],
                [14, 'RainLab\\Notify\\Models\\NotificationRule', 'rule_actions',    'Rainlab\\Notify\\Models\\RuleAction',    '15', 'CjHIBDTsxqogMBl6wiwyIb9ugWpnXnlbKyNsd6iP',   1, '2020-05-29 00:24:59', '2020-05-29 00:24:59'],
                [15, 'RainLab\\Notify\\Models\\NotificationRule', 'rule_conditions', 'RainLab\\Notify\\Models\\RuleCondition', '17', 'gjlVPq3XFdF3MGXwOl0stBv5kx9f0CNsHeofwiYy',   1, '2020-05-29 00:32:10', '2020-05-29 00:32:10']
            ]
        );

        /** Populate rainlab_notify_notification_rules table. */
        Db::insert(
            'INSERT INTO rainlab_notify_notification_rules (id, name, code, class_name, description, config_data, condition_data, is_enabled, is_custom, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                [1,  'Notify school via database that a teacher requested a course',                '', 'Genuineq\\Tms\\NotifyRules\\TeacherCourseRequestEvent',    '', NULL, NULL, 1, 1, '2020-05-28 22:57:08', '2020-05-29 00:23:43'],
                [2,  'Notify school via email that a teacher requested a course',                   '', 'Genuineq\\Tms\\NotifyRules\\TeacherCourseRequestEvent',    '', NULL, NULL, 1, 1, '2020-05-29 00:21:24', '2020-05-29 00:23:58'],
                [3,  'Notify teacher via email that a school requested a course',                   '', 'Genuineq\\Tms\\NotifyRules\\SchoolCourseRequestEvent',     '', NULL, NULL, 1, 1, '2020-05-29 00:26:42', '2020-05-29 00:27:32'],
                [4,  'Notify teacher via database that a school requested a course',                '', 'Genuineq\\Tms\\NotifyRules\\SchoolCourseRequestEvent',     '', NULL, NULL, 1, 1, '2020-05-29 00:29:41', '2020-05-29 00:29:41'],
                [5,  'Notify school via email that a teacher accepted a course proposal',           '', 'Genuineq\\Tms\\NotifyRules\\TeacherCourseApproveEvent',    '', NULL, NULL, 1, 1, '2020-05-29 00:31:19', '2020-05-29 00:31:19'],
                [6,  'Notify school via database that a teacher accepted a course proposal',        '', 'Genuineq\\Tms\\NotifyRules\\TeacherCourseApproveEvent',    '', NULL, NULL, 1, 1, '2020-05-29 00:31:54', '2020-05-29 00:31:54'],
                [7,  'Notify school via email that a teacher rejected a course proposal',           '', 'Genuineq\\Tms\\NotifyRules\\TeacherCourseRejectEvent',     '', NULL, NULL, 1, 1, '2020-05-29 00:33:13', '2020-05-29 00:33:13'],
                [8,  'Notify school via database that a teacher rejected a course proposal',        '', 'Genuineq\\Tms\\NotifyRules\\TeacherCourseRejectEvent',     '', NULL, NULL, 1, 1, '2020-05-29 00:33:57', '2020-05-29 00:33:57'],
                [9,  'Notify teacher via email that a school accepted a course proposal',           '', 'Genuineq\\Tms\\NotifyRules\\SchoolCourseApproveEvent',     '', NULL, NULL, 1, 1, '2020-05-29 00:35:17', '2020-05-29 00:35:17'],
                [10, 'Notify teacher via database that a school accepted a course proposal',        '', 'Genuineq\\Tms\\NotifyRules\\SchoolCourseApproveEvent',     '', NULL, NULL, 1, 1, '2020-05-29 00:35:58', '2020-05-29 00:35:58'],
                [11, 'Notify teacher via email that a school rejected a course proposal',           '', 'Genuineq\\Tms\\NotifyRules\\SchoolCourseRejectEvent',      '', NULL, NULL, 1, 1, '2020-05-29 00:37:03', '2020-05-29 00:37:03'],
                [12, 'Notify teacher via database that a school rejected a course proposal',        '', 'Genuineq\\Tms\\NotifyRules\\SchoolCourseRejectEvent',      '', NULL, NULL, 1, 1, '2020-05-29 00:38:01', '2020-05-29 00:38:01'],
                [13, 'Notify school via email that a teacher added the appraisal objectives',       '', 'Genuineq\\Tms\\NotifyRules\\TeacherObjectivesSetEvent',    '', NULL, NULL, 1, 1, '2020-05-30 01:13:41', '2020-05-30 01:13:41'],
                [14, 'Notify school via database that a teacher added the appraisal objectives',    '', 'Genuineq\\Tms\\NotifyRules\\TeacherObjectivesSetEvent',    '', NULL, NULL, 1, 1, '2020-05-30 01:14:51', '2020-05-30 01:14:51'],
                [15, 'Notify teacher via email that a school approved appraisal the objectives',    '', 'Genuineq\\Tms\\NotifyRules\\SchoolObjectivesApproveEvent', '', NULL, NULL, 1, 1, '2020-05-30 01:16:06', '2020-05-30 01:18:40'],
                [16, 'Notify teacher via database that a school approved the appraisal objectives', '', 'Genuineq\\Tms\\NotifyRules\\SchoolObjectivesApproveEvent', '', NULL, NULL, 1, 1, '2020-05-30 01:17:01', '2020-05-30 01:17:18'],
                [17, 'Notify teacher via email that a school the appraisal skills',                 '', 'Genuineq\\Tms\\NotifyRules\\SchoolSkillsSetEvent',         '', NULL, NULL, 1, 1, '2020-05-30 01:19:19', '2020-05-30 01:19:19'],
                [18, 'Notify teacher via database that a school the appraisal skills',              '', 'Genuineq\\Tms\\NotifyRules\\SchoolSkillsSetEvent',         '', NULL, NULL, 1, 1, '2020-05-30 01:19:38', '2020-05-30 01:19:38'],
                [19, 'Notify teacher via email that a school completed the appraisal',              '', 'Genuineq\\Tms\\NotifyRules\\SchoolEvaluationClosedEvent',  '', NULL, NULL, 1, 1, '2020-05-30 01:21:12', '2020-05-30 01:21:12'],
                [20, 'Notify teacher via database that a school completed the appraisal',           '', 'Genuineq\\Tms\\NotifyRules\\SchoolEvaluationClosedEvent',  '', NULL, NULL, 1, 1, '2020-05-30 01:21:22', '2020-05-30 01:21:22']
            ]
        );

        /** Populate rainlab_notify_rule_actions table. */
        Db::insert(
            'INSERT INTO rainlab_notify_rule_actions (id, class_name, config_data, rule_host_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?)',
            [
                [1,  'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '[]',                                                                                                                                                                                                                                                                                                          NULL, '2020-05-28 22:17:09', '2020-05-28 22:17:09'],
                [2,  'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '[]',                                                                                                                                                                                                                                                                                                          NULL, '2020-05-28 22:17:54', '2020-05-28 22:17:54'],
                [3,  'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        5, '2020-05-28 22:56:29', '2020-05-28 22:57:09'],
                [4,  'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.teacher-course-request\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.teacher-course-request\"}',       6, '2020-05-29 00:21:00', '2020-05-29 00:21:24'],
                [5,  'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.school-course-request\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.school-course-request\"}',         7, '2020-05-29 00:26:11', '2020-05-29 00:26:42'],
                [6,  'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        8, '2020-05-29 00:29:21', '2020-05-29 00:29:41'],
                [7,  'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.teacher-course-approve\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.teacher-course-approve\"}',       9, '2020-05-29 00:30:44', '2020-05-29 00:31:19'],
                [8,  'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        10, '2020-05-29 00:31:41', '2020-05-29 00:31:54'],
                [9,  'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.teacher-course-reject\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.teacher-course-reject\"}',         11, '2020-05-29 00:32:42', '2020-05-29 00:33:13'],
                [10, 'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        12, '2020-05-29 00:33:43', '2020-05-29 00:33:57'],
                [11, 'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.school-course-approve\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.school-course-approve\"}',         13, '2020-05-29 00:34:27', '2020-05-29 00:35:17'],
                [12, 'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        14, '2020-05-29 00:35:50', '2020-05-29 00:35:58'],
                [13, 'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.school-course-reject\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.school-course-reject\"}',           15, '2020-05-29 00:36:25', '2020-05-29 00:37:03'],
                [14, 'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        16, '2020-05-29 00:37:30', '2020-05-29 00:38:01'],
                [15, 'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.teacher-objectives-set\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.teacher-objectives-set\"}',       17, '2020-05-30 01:12:00', '2020-05-30 01:13:41'],
                [16, 'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        18, '2020-05-30 01:14:39', '2020-05-30 01:14:51'],
                [17, 'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.school-objectives-approve\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.school-objectives-approve\"}', 19, '2020-05-30 01:15:52', '2020-05-30 01:16:06'],
                [18, 'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        20, '2020-05-30 01:16:45', '2020-05-30 01:17:01'],
                [19, 'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.school-skills-set\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.school-skills-set\"}',                 21, '2020-05-30 01:18:34', '2020-05-30 01:19:20'],
                [20, 'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        22, '2020-05-30 01:19:25', '2020-05-30 01:19:38'],
                [21, 'RainLab\\Notify\\NotifyRules\\SendMailTemplateAction', '{\"mail_template\":\"genuineq.tms::mail.school-evaluation-close\",\"send_to_mode\":\"user\",\"send_to_custom\":\"\",\"send_to_admin\":\"\",\"reply_to_custom\":\"\",\"action_text\":\"Send a message to user email address (if applicable) using template genuineq.tms::mail.school-evaluation-close\"}',     23, '2020-05-30 01:20:33', '2020-05-30 01:21:12'],
                [22, 'RainLab\\Notify\\NotifyRules\\SaveDatabaseAction',     '{\"related_object\":\"RainLab\\\\User\\\\Models\\\\User@notifications\",\"action_text\":\"Log event in the User notifications log\"}',                                                                                                                                                                        24, '2020-05-30 01:20:44', '2020-05-30 01:21:22']

            ]
        );

        /** Populate rainlab_notify_rule_conditions table. */
        Db::insert(
            'INSERT INTO rainlab_notify_rule_conditions (id, class_name, config_data, condition_control_type, rule_host_type, rule_host_id, rule_parent_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                [1,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', NULL, NULL, '2020-05-28 17:40:02', '2020-05-28 17:40:02'],
                [2,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', NULL, NULL, '2020-05-28 19:34:41', '2020-05-28 19:34:41'],
                [3,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', NULL, NULL, '2020-05-28 19:45:26', '2020-05-28 19:45:26'],
                [4,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', NULL, NULL, '2020-05-28 21:55:35', '2020-05-28 21:55:35'],
                [5,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 5,    NULL, '2020-05-28 22:54:36', '2020-05-28 22:57:08'],
                [6,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 6,    NULL, '2020-05-29 00:19:54', '2020-05-29 00:21:24'],
                [7,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":\"0\",\"condition\":\"true\",\"condition_text\":\"ALL of subconditions should be TRUE\"}',                                                          NULL,  'any', 7,    NULL, '2020-05-29 00:24:37', '2020-05-29 01:22:49'],
                [8,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 8,    NULL, '2020-05-29 00:29:11', '2020-05-29 00:29:41'],
                [9,  'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 9,    NULL, '2020-05-29 00:30:13', '2020-05-29 00:31:19'],
                [10, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 10,   NULL, '2020-05-29 00:31:33', '2020-05-29 00:31:54'],
                [11, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', NULL, NULL, '2020-05-29 00:32:10', '2020-05-29 00:32:10'],
                [12, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 11,   NULL, '2020-05-29 00:32:24', '2020-05-29 00:33:13'],
                [13, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 12,   NULL, '2020-05-29 00:33:34', '2020-05-29 00:33:57'],
                [14, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 13,   NULL, '2020-05-29 00:34:20', '2020-05-29 00:35:17'],
                [15, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 14,   NULL, '2020-05-29 00:35:34', '2020-05-29 00:35:58'],
                [16, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 15,   NULL, '2020-05-29 00:36:17', '2020-05-29 00:37:03'],
                [17, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 16,   NULL, '2020-05-29 00:37:23', '2020-05-29 00:38:01'],
                [18, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 11,   '2020-05-29 02:07:28', '2020-05-29 02:07:38'],
                [19, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 14,   '2020-05-29 02:10:05', '2020-05-29 02:10:15'],
                [20, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 18,   '2020-05-29 02:10:30', '2020-05-29 02:10:41'],
                [21, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 9,    '2020-05-29 02:11:08', '2020-05-29 02:11:17'],
                [22, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 21,   '2020-05-29 02:11:40', '2020-05-29 02:11:48'],
                [23, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 24,   '2020-05-29 02:12:02', '2020-05-29 02:12:14'],
                [24, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 17,   NULL, '2020-05-30 01:11:52', '2020-05-30 01:13:41'],
                [25, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 35,   '2020-05-30 01:12:50', '2020-05-30 01:13:41'],
                [26, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 18,   NULL, '2020-05-30 01:14:20', '2020-05-30 01:14:51'],
                [27, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 19,   NULL, '2020-05-30 01:15:15', '2020-05-30 01:16:06'],
                [28, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 38,   '2020-05-30 01:16:21', '2020-05-30 01:16:28'],
                [29, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 20,   NULL, '2020-05-30 01:16:38', '2020-05-30 01:17:01'],
                [30, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 21,   NULL, '2020-05-30 01:18:02', '2020-05-30 01:19:20'],
                [31, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 41,   '2020-05-30 01:19:03', '2020-05-30 01:19:20'],
                [32, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 22,   NULL, '2020-05-30 01:19:09', '2020-05-30 01:19:38'],
                [33, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 23,   NULL, '2020-05-30 01:19:58', '2020-05-30 01:21:12'],
                [34, 'RainLab\\Notify\\Classes\\CompoundCondition',         '{\"condition_type\":0,\"condition\":\"true\"}',                                                                                                                         NULL,  'any', 24,   NULL, '2020-05-30 01:20:08', '2020-05-30 01:21:22'],
                [35, 'Genuineq\\User\\NotifyRules\\UserAttributeCondition', '{\"subcondition\":\"email_notifications\",\"operator\":\"is\",\"value\":\"1\",\"condition_text\":\"Email Notifications <span class=\\\"operator\\\">is<\\/span> 1\"}', 'text', 'any', NULL, 44,   '2020-05-30 01:21:01', '2020-05-30 01:21:12']
            ]
        );
    }
}
