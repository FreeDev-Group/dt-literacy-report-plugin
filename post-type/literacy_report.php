<?php
/**
 * Literacy Report Post Type
 *
 * Uses DT's built-in Disciple_Tools_Post_Type_Template class
 * which handles: post type registration, navigation tab, templates,
 * permissions, rewrite rules, p2p connections — everything.
 *
 * @package Disciple_Tools_Literacy_Reports
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// ============================================================
// 1. REGISTER POST TYPE (using DT's native class)
// ============================================================
new Disciple_Tools_Post_Type_Template( 'literacy_report', 'Literacy Report', 'Literacy Reports' );

// ============================================================
// 2. REGISTER MODULE
// ============================================================
add_filter( 'dt_post_type_modules', function( $modules ) {
    $modules['literacy_report'] = [
        'name'        => 'Literacy Reports',
        'enabled'     => true,
        'locked'      => true,
        'description' => 'Track class attendance, book/lesson progress, and teacher reporting.',
        'post_type'   => 'literacy_report',
        'singular'    => 'Literacy Report',
        'plural'      => 'Literacy Reports',
    ];
    return $modules;
}, 10, 1 );

// ============================================================
// 3. FIELDS
// ============================================================
add_filter( 'dt_custom_fields_settings', function( $fields, $post_type ) {
    if ( $post_type !== 'literacy_report' ) { return $fields; }

    // --- Status ---
    $fields['status'] = [
        'name' => 'Status',
        'type' => 'key_select',
        'default' => [
            'new'            => [ 'label' => 'New', 'color' => '#F43636' ],
            'reviewed'       => [ 'label' => 'Reviewed', 'color' => '#FF9800' ],
            'approved'       => [ 'label' => 'Approved', 'color' => '#4CAF50' ],
            'needs_followup' => [ 'label' => 'Needs Follow-up', 'color' => '#2196F3' ],
            'closed'         => [ 'label' => 'Closed', 'color' => '#808080' ],
        ],
        'tile' => 'status',
        'in_create_form' => false,
        'select_cannot_be_empty' => true,
    ];

    // --- Report Date ---
    $fields['report_date'] = [
        'name' => 'Report Date',
        'type' => 'date',
        'tile' => 'status',
        'in_create_form' => true,
        'icon' => get_template_directory_uri() . '/dt-assets/images/calendar-range.svg',
    ];

    // --- Submission Source ---
    $fields['submission_source'] = [
        'name' => 'Submission Source',
        'type' => 'key_select',
        'default' => [
            'pwa_online'  => [ 'label' => 'PWA (Online)' ],
            'pwa_offline' => [ 'label' => 'PWA (Synced from Offline)' ],
            'manual'      => [ 'label' => 'Manual Entry' ],
        ],
        'tile' => 'status',
    ];

    // --- Teacher Code ---
    $fields['teacher_code'] = [
        'name' => 'Teacher Code',
        'type' => 'text',
        'tile' => 'report_details',
        'in_create_form' => true,
    ];

    // --- Connection: Class (Group) ---
    $fields['report_class'] = [
        'name' => 'Class',
        'type' => 'connection',
        'post_type' => 'groups',
        'p2p_direction' => 'from',
        'p2p_key' => 'literacy_report_to_groups',
        'tile' => 'report_details',
        'in_create_form' => true,
    ];

    // --- Connection: Teacher (Contact) ---
    $fields['report_teacher'] = [
        'name' => 'Teacher',
        'type' => 'connection',
        'post_type' => 'contacts',
        'p2p_direction' => 'from',
        'p2p_key' => 'literacy_report_to_contacts',
        'tile' => 'report_details',
        'in_create_form' => true,
    ];

    // --- Students Present ---
    $fields['students_present'] = [
        'name' => 'Students Present',
        'type' => 'connection',
        'post_type' => 'contacts',
        'p2p_direction' => 'from',
        'p2p_key' => 'literacy_report_students_present',
        'tile' => 'attendance',
    ];

    // --- Students Absent ---
    $fields['students_absent'] = [
        'name' => 'Students Absent',
        'type' => 'connection',
        'post_type' => 'contacts',
        'p2p_direction' => 'from',
        'p2p_key' => 'literacy_report_students_absent',
        'tile' => 'attendance',
    ];

    // --- Attendance Count ---
    $fields['attendance_count'] = [
        'name' => 'Students Present Count',
        'type' => 'number',
        'tile' => 'attendance',
        'in_create_form' => false,
    ];

    // --- Total Students ---
    $fields['total_students'] = [
        'name' => 'Total Students',
        'type' => 'number',
        'tile' => 'attendance',
        'in_create_form' => false,
    ];

    // --- Attendance Rate ---
    $fields['attendance_rate'] = [
        'name' => 'Attendance Rate (%)',
        'type' => 'number',
        'tile' => 'attendance',
        'in_create_form' => false,
    ];

    // --- Current Book ---
    $fields['current_book'] = [
        'name' => 'Current Book',
        'type' => 'key_select',
        'default' => [
            'book_1' => [ 'label' => 'Book 1 - Alphabet' ],
            'book_2' => [ 'label' => 'Book 2 - Syllables' ],
            'book_3' => [ 'label' => 'Book 3 - Words' ],
            'book_4' => [ 'label' => 'Book 4 - Sentences' ],
            'book_5' => [ 'label' => 'Book 5 - Reading' ],
        ],
        'tile' => 'progression',
        'in_create_form' => true,
    ];

    // --- Current Lesson ---
    $fields['current_lesson'] = [
        'name' => 'Current Lesson',
        'type' => 'key_select',
        'default' => array_combine(
            array_map( fn( $i ) => "lesson_$i", range( 1, 30 ) ),
            array_map( fn( $i ) => [ 'label' => "Lesson $i" ], range( 1, 30 ) )
        ),
        'tile' => 'progression',
        'in_create_form' => true,
    ];

    return $fields;
}, 10, 2 );

// ============================================================
// 4. TILES
// ============================================================
add_filter( 'dt_details_additional_tiles', function( $tiles, $post_type ) {
    if ( $post_type !== 'literacy_report' ) { return $tiles; }
    $tiles['status']         = [ 'label' => 'Status' ];
    $tiles['report_details'] = [ 'label' => 'Report Details' ];
    $tiles['attendance']     = [ 'label' => 'Attendance' ];
    $tiles['progression']    = [ 'label' => 'Lesson Progression' ];
    return $tiles;
}, 10, 2 );

// ============================================================
// 5. CONNECTION FIELDS ON GROUPS
// ============================================================
add_filter( 'dt_custom_fields_settings', function( $fields, $post_type ) {
    if ( $post_type !== 'groups' ) { return $fields; }
    $fields['literacy_reports'] = [
        'name' => 'Literacy Reports',
        'type' => 'connection',
        'post_type' => 'literacy_report',
        'p2p_direction' => 'to',
        'p2p_key' => 'literacy_report_to_groups',
        'tile' => 'other',
    ];
    return $fields;
}, 50, 2 );

// ============================================================
// 6. CONNECTION FIELDS ON CONTACTS
// ============================================================
add_filter( 'dt_custom_fields_settings', function( $fields, $post_type ) {
    if ( $post_type !== 'contacts' ) { return $fields; }
    $fields['literacy_reports_as_teacher'] = [
        'name' => 'Literacy Reports (Teacher)',
        'type' => 'connection',
        'post_type' => 'literacy_report',
        'p2p_direction' => 'to',
        'p2p_key' => 'literacy_report_to_contacts',
        'tile' => 'other',
    ];
    $fields['literacy_reports_attendance'] = [
        'name' => 'Literacy Reports (Attendance)',
        'type' => 'connection',
        'post_type' => 'literacy_report',
        'p2p_direction' => 'to',
        'p2p_key' => 'literacy_report_students_present',
        'tile' => 'other',
    ];
    return $fields;
}, 50, 2 );

// ============================================================
// 7. PERMISSIONS
// ============================================================
add_filter( 'dt_set_roles_and_permissions', function( $expected_roles ) {
    $perms = [
        'access_literacy_report',
        'create_literacy_report',
        'view_any_literacy_report',
        'update_any_literacy_report',
        'dt_all_access_literacy_report',
        'delete_any_literacy_report',
        'dt_all_admin_literacy_report',
    ];
    $roles = [ 'administrator', 'dt_admin', 'multiplier', 'dispatcher', 'marketer', 'strategist' ];
    foreach ( $roles as $role ) {
        if ( isset( $expected_roles[$role] ) ) {
            foreach ( $perms as $perm ) {
                $expected_roles[$role]['permissions'][$perm] = true;
            }
        }
    }
    return $expected_roles;
}, 20, 1 );

// ============================================================
// 8. DEFAULT STATUS ON CREATE
// ============================================================
add_action( 'dt_post_created', function( $post_type, $post_id, $initial_fields ) {
    if ( $post_type !== 'literacy_report' ) { return; }
    if ( empty( $initial_fields['status'] ) ) {
        DT_Posts::update_post( 'literacy_report', $post_id, [ 'status' => 'new' ], true, false );
    }
}, 10, 3 );

// ============================================================
// 9. AUTOMATIC ATTENDANCE CALCULATIONS
// ============================================================

/**
 * Extract unique post IDs from a Disciple.Tools connection field.
 *
 * @param mixed $connections Connection field value.
 * @return int[]
 */
function dt_literacy_reports_connection_ids( $connections ) {
    if ( ! is_array( $connections ) ) {
        return [];
    }

    $ids = [];
    foreach ( $connections as $connection ) {
        if ( is_array( $connection ) ) {
            $id = $connection['ID'] ?? $connection['id'] ?? $connection['value'] ?? 0;
        } else {
            $id = $connection;
        }

        $id = absint( $id );
        if ( $id > 0 ) {
            $ids[] = $id;
        }
    }

    return array_values( array_unique( $ids ) );
}

/**
 * Recalculate and persist attendance summary fields.
 *
 * Present students take precedence when a contact occurs in both attendance
 * lists. The total is the number of unique contacts across both lists.
 *
 * @param int        $post_id Literacy report ID.
 * @param array|null $report  Processed Disciple.Tools post fields.
 * @return void
 */
function dt_literacy_reports_recalculate_attendance( $post_id, $report = null ) {
    static $is_recalculating = false;

    if ( $is_recalculating ) {
        return;
    }

    if ( ! is_array( $report ) ) {
        $report = DT_Posts::get_post( 'literacy_report', $post_id );
    }

    if ( ! is_array( $report ) || is_wp_error( $report ) ) {
        return;
    }

    $present_ids = dt_literacy_reports_connection_ids( $report['students_present'] ?? [] );
    $absent_ids  = dt_literacy_reports_connection_ids( $report['students_absent'] ?? [] );
    $student_ids = array_unique( array_merge( $present_ids, $absent_ids ) );

    $attendance_count = count( $present_ids );
    $total_students    = count( $student_ids );
    $attendance_rate   = $total_students > 0
        ? round( ( $attendance_count / $total_students ) * 100, 2 )
        : 0;

    $calculated_fields = [
        'attendance_count' => $attendance_count,
        'total_students'   => $total_students,
        'attendance_rate'  => $attendance_rate,
    ];

    $changed_fields = [];
    foreach ( $calculated_fields as $field_key => $value ) {
        if ( ! isset( $report[$field_key] ) || (float) $report[$field_key] !== (float) $value ) {
            $changed_fields[$field_key] = $value;
        }
    }

    if ( empty( $changed_fields ) ) {
        return;
    }

    $is_recalculating = true;
    DT_Posts::update_post( 'literacy_report', $post_id, $changed_fields, true, false );
    $is_recalculating = false;
}

add_action( 'dt_post_created', function( $post_type, $post_id ) {
    if ( $post_type !== 'literacy_report' ) {
        return;
    }

    dt_literacy_reports_recalculate_attendance( $post_id );
}, 20, 2 );

add_action( 'dt_post_updated', function( $post_type, $post_id, $initial_fields, $fields_before, $fields_after ) {
    if ( $post_type !== 'literacy_report' ) {
        return;
    }

    dt_literacy_reports_recalculate_attendance( $post_id, $fields_after );
}, 20, 5 );
