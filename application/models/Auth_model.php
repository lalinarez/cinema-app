<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Session_model');
    }

    private function _send_email_(array $builder): void
    {
        $this->load->library('email');

        $this->email
            ->initialize([
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.gmail.com',
                'smtp_port' => 465,
                'smtp_user' => GMAIL['EMAIL'],
                'smtp_pass' => GMAIL['PASSWORD'],
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n"
            ])
            ->from(GMAIL['EMAIL'])
            ->to($builder['to'])
            ->subject($builder['subject'])
            ->message($builder['message'])
            ->send();
    }

    private function _set_userdata_(array $data = array()): void
    {
        $this->session->set_userdata($data);
    }

    private function _get_role_(int $role_id): string | null
    {
        return match ($role_id) {
            1 => 'is_admin',
            2 => 'is_user',
            3 => 'is_guest',
            default => NULL
        };
    }

    public function login(array $data): string
    {
        $response = $this->db->query(sprintf("SELECT id_user, id_contact, id_rol, id_status, user_username, user_email, user_password, user_avatar FROM cm_users WHERE user_email = '%s' AND id_status = 1 LIMIT 1", $data['email']));

        if ($response->num_rows() == 0) {
            return 'not-found';
        }

        $user = $response->row_array();

        if (!password_verify($data['password'], $user['user_password'])) {
            return 'not-match';
        }

        $this->_set_userdata_([
            'is_authorized' => true,
            'id' => $user['id_user'],
            $this->_get_role_($user['id_rol']) => true
        ]);

        $store = $this->Session_model->store($user['id_user']);

        return 'success';
    }

    public function logout(): string
    {
        $this->_set_userdata_();
        $this->session->sess_destroy();

        return 'success';
    }
}
