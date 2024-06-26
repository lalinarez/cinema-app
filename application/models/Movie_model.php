<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Movie_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index(array $builder = array()): object
	{
		$builder['columns'] = $builder['columns'] ?? 'id_movie, cm_movies.id_status, cm_status.status_name, cm_movies.id_quality, cm_qualities.quality_name, cm_movies.movie_name, cm_movies.movie_slug, cm_movies.movie_description, cm_movies.movie_release_date, cm_movies.movie_duration, cm_movies.movie_country_origin, cm_movies.movie_cover, cm_movies.movie_reproductions, cm_movies.movie_play, cm_movies.is_premiere, cm_movies.ip_registered_mov, cm_movies.date_registered_mov, cm_movies.client_registered_mov, cm_movies.ip_modified_mov, cm_movies.date_modified_mov, cm_movies.client_modified_mov';
		$builder['order_column'] = $builder['order_column'] ?? 'id_movie';
		$builder['order_filter'] = $builder['order_filter'] ?? 'DESC';
		$builder['start'] = $builder['start'] ?? 0;

		$response = $this->db
			->select($builder['columns'])
			->from('cm_movies')
			->join('cm_status', 'cm_status.id_status = cm_movies.id_status')
			->join('cm_qualities', 'cm_qualities.id_quality = cm_movies.id_quality');

		if (isset($builder['status'])) {
			$response = $response->where('cm_movies.id_status', $builder['status']);
		}

		$response = $response->order_by($builder['order_column'], $builder['order_filter']);

		if (isset($builder['limit'])) {
			$response = $response->limit($builder['limit'], $builder['start']);
		}

		$response = $response->get();

		return $response;
	}

	public function store(array $data): string
	{
		$response = $this->db
			->select('id_movie')
			->where('id_status', $data['status_id'])
			->where('id_quality', $data['quality_id'])
			->where('movie_name', $data['title'])
			->where('movie_description', $data['description'])
			->where('movie_release_date', $data['release_date'])
			->where('movie_play', $data['link'])
			->limit(1)
			->get('cm_movies');

		if ($response->num_rows() > 0) {
			return 'existing';
		}

		$store = $this->db->insert('cm_movies', [
			'id_status' => $data['status_id'],
			'id_quality' => $data['quality_id'],
			'movie_name' => $data['title'],
			'movie_slug' => url_title(remove_accents($data['title']), '-', true),
			'movie_description' => $data['description'],
			'movie_release_date' => $data['release_date'],
			'movie_duration' => $data['duration'],
			'movie_country_origin' => $data['country_origin'],
			'movie_cover' => 'NO-IMAGE',
			'movie_reproductions' => 0,
			'movie_play' => $data['link'],
			'is_premiere' => 0,
			'ip_registered_mov' => get_current_ip(),
			'date_registered_mov' => get_current_date(),
			'client_registered_mov' => get_current_agent()
		]);

		$last_id = $this->db->insert_id();

		foreach ($data['productors'] as $productor) {
			$this->db->insert('cm_pro_mov', ['id_productor' => $productor, 'id_movie' => $last_id]);
		}

		foreach ($data['genders'] as $gender) {
			$this->db->insert('cm_gen_mov', ['id_gender' => $gender, 'id_movie' => $last_id]);
		}

		foreach ($data['categories'] as $category) {
			$this->db->insert('cm_cat_mov', ['id_category' => $category, 'id_movie' => $last_id]);
		}

		return ($store ? 'success' : 'error');
	}

	public function fetch(array $builder = array()): object
	{
		$builder['columns'] = $builder['columns'] ?? 'id_movie, cm_movies.id_status, cm_status.status_name, cm_movies.id_quality, cm_qualities.quality_name, cm_movies.movie_name, cm_movies.movie_slug, cm_movies.movie_description, cm_movies.movie_release_date, cm_movies.movie_duration, cm_movies.movie_country_origin, cm_movies.movie_cover, cm_movies.movie_reproductions, cm_movies.movie_play, cm_movies.is_premiere, cm_movies.ip_registered_mov, cm_movies.date_registered_mov, cm_movies.client_registered_mov, cm_movies.ip_modified_mov, cm_movies.date_modified_mov, cm_movies.client_modified_mov';
		$builder['search'] = $builder['search'] ?? 'id_movie';

		$response = $this->db
			->select($builder['columns'])
			->from('cm_movies')
			->join('cm_status', 'cm_status.id_status = cm_movies.id_status')
			->join('cm_qualities', 'cm_qualities.id_quality = cm_movies.id_quality')
			->where($builder['search'], ((isset($builder['decrypt']) and $builder['decrypt'] == true) ? decrypt($builder['value']) : $builder['value']))
			->limit(1)
			->get();

		return $response;
	}

	public function productors_by_movie(array $builder = array()): object
	{
		$builder['columns'] = $builder['columns'] ?? 'cm_pro_mov.id_productor, cm_productors.id_status, productor_name, productor_slug, productor_image_logo, cm_pro_mov.id_movie, cm_movies.id_status, id_quality, movie_name, movie_slug, movie_description, movie_release_date, movie_duration, movie_country_origin, movie_cover, movie_reproductions, movie_play, is_premiere';
		$builder['order_column'] = $builder['order_column'] ?? 'cm_pro_mov.id_productor';
		$builder['order_filter'] = $builder['order_filter'] ?? 'DESC';
		$builder['search'] = $builder['search'] ?? 'cm_pro_mov.id_movie';
		$builder['start'] = $builder['start'] ?? 0;

		$response = $this->db
			->select($builder['columns'])
			->from('cm_pro_mov')
			->join('cm_productors', 'cm_productors.id_productor = cm_pro_mov.id_productor')
			->join('cm_movies', 'cm_movies.id_movie = cm_pro_mov.id_movie');

		if (isset($builder['search']) && isset($builder['value'])) {
			$response = $response->where($builder['search'], ((isset($builder['decrypt']) and $builder['decrypt'] == true) ? decrypt($builder['value']) : $builder['value']));
		}

		$response = $response->order_by($builder['order_column'], $builder['order_filter']);

		if (isset($builder['limit'])) {
			$response = $response->limit($builder['limit'], $builder['start']);
		}

		$response = $response->get();

		return $response;
	}

	public function genders_by_movie(array $builder = array()): object
	{
		$builder['columns'] = $builder['columns'] ?? 'cm_gen_mov.id_gender, cm_genders.id_status, gender_name, gender_slug, cm_gen_mov.id_movie, cm_movies.id_status, id_quality	, movie_name, movie_slug, movie_description, movie_release_date, movie_duration, movie_country_origin, movie_cover, movie_reproductions, movie_play, is_premiere';
		$builder['order_column'] = $builder['order_column'] ?? 'cm_gen_mov.id_gender';
		$builder['order_filter'] = $builder['order_filter'] ?? 'DESC';
		$builder['search'] = $builder['search'] ?? 'cm_gen_mov.id_movie';
		$builder['start'] = $builder['start'] ?? 0;

		$response = $this->db
			->select($builder['columns'])
			->from('cm_gen_mov')
			->join('cm_genders', 'cm_genders.id_gender = cm_gen_mov.id_gender')
			->join('cm_movies', 'cm_movies.id_movie = cm_gen_mov.id_movie');

		if (isset($builder['search']) && isset($builder['value'])) {
			$response = $response->where($builder['search'], ((isset($builder['decrypt']) and $builder['decrypt'] == true) ? decrypt($builder['value']) : $builder['value']));
		}

		$response = $response->order_by($builder['order_column'], $builder['order_filter']);

		if (isset($builder['limit'])) {
			$response = $response->limit($builder['limit'], $builder['start']);
		}

		$response = $response->get();

		return $response;
	}

	public function categories_by_movie(array $builder = array()): object
	{
		$builder['columns'] = $builder['columns'] ?? 'cm_cat_mov.id_category, cm_categories.id_status, category_name, category_slug, cm_cat_mov.id_movie, cm_movies.id_status, id_quality, movie_name, movie_slug, movie_description, movie_release_date, movie_duration, movie_country_origin, movie_cover, movie_reproductions, movie_play, is_premiere';
		$builder['order_column'] = $builder['order_column'] ?? 'cm_cat_mov.id_category';
		$builder['order_filter'] = $builder['order_filter'] ?? 'DESC';
		$builder['search'] = $builder['search'] ?? 'cm_cat_mov.id_movie';
		$builder['start'] = $builder['start'] ?? 0;

		$response = $this->db
			->select($builder['columns'])
			->from('cm_cat_mov')
			->join('cm_categories', 'cm_categories.id_category = cm_cat_mov.id_category')
			->join('cm_movies', 'cm_movies.id_movie = cm_cat_mov.id_movie');

		if (isset($builder['search']) && isset($builder['value'])) {
			$response = $response->where($builder['search'], ((isset($builder['decrypt']) and $builder['decrypt'] == true) ? decrypt($builder['value']) : $builder['value']));
		}

		$response = $response->order_by($builder['order_column'], $builder['order_filter']);

		if (isset($builder['limit'])) {
			$response = $response->limit($builder['limit'], $builder['start']);
		}

		$response = $response->get();

		return $response;
	}

	public function update(array $data): string
	{
		$id = $data['id'];

		$response = $this->db
			->select('id_movie')
			->where('id_status', $data['status_id'])
			->where('id_quality', $data['quality_id'])
			->where('movie_name', $data['title'])
			->where('movie_description', $data['description'])
			->where('movie_release_date', $data['release_date'])
			->limit(1)
			->get('cm_movies');

		if ($response->num_rows() > 0) {
			return 'existing';
		}

		$update = $this->db
			->where('id_movie', $id)
			->update('cm_movies', [
				'id_status' => $data['status_id'],
				'id_quality' => $data['quality_id'],
				'movie_name' => $data['title'],
				'movie_slug' => url_title(remove_accents($data['title']), '-', true),
				'movie_release_date' => $data['release_date'],
				'movie_duration' => $data['duration'],
				'movie_country_origin' => $data['country_origin'],
				'movie_description' => $data['description'],
				'movie_play' => $data['link'],
				'ip_modified_mov' => get_current_ip(),
				'date_modified_mov' => get_current_date(),
				'client_modified_mov' => get_current_agent()
			]);

		$this->db->where('id_movie', $id)->delete('cm_gen_mov');
		$this->db->where('id_movie', $id)->delete('cm_pro_mov');
		$this->db->where('id_movie', $id)->delete('cm_cat_mov');

		foreach ($data['productors'] as $productor) {
			$this->db->insert('cm_pro_mov', ['id_productor' => $productor, 'id_movie' => $id]);
		}

		foreach ($data['genders'] as $gender) {
			$this->db->insert('cm_gen_mov', ['id_gender' => $gender, 'id_movie' => $id]);
		}

		foreach ($data['categories'] as $category) {
			$this->db->insert('cm_cat_mov', ['id_category' => $category, 'id_movie' => $id]);
		}

		return ($update ? 'success' : 'error');
	}

	public function increase_views(string $slug): void
	{
		$response = $this->db
			->select('movie_reproductions')
			->where('movie_slug', $slug)
			->limit(1)
			->get('cm_movies');

		if ($response->num_rows() > 0) {
			$movie = $response->row_array();

			$this->db->where('movie_slug', $slug)->update('cm_movies', [
				'movie_reproductions' => ($movie['movie_reproductions'] + 1)
			]);
		}
	}

	public function update_cover(array $data): string
	{
		$id = $data['id'];

		$response = $this->db
			->select('movie_cover')
			->where('id_movie', $id)
			->limit(1)
			->get('cm_movies');

		if ($response->num_rows() == 0) {
			return 'not-found';
		}

		$movie = $response->row_array();

		$new_cover = FOLDER_MOVIES . "/{$data['cover']}";
		$end_cover = FOLDER_MOVIES . "/{$id}_cover.png";

		if (strcmp($movie['movie_cover'], 'NO-IMAGE') != 0) {
			unlink($movie['movie_cover']);
		}

		rename($new_cover, $end_cover);

		$update = $this->db
			->where('id_movie', $id)
			->update('cm_movies', [
				'movie_cover' => $end_cover
			]);

		return ($update ? 'success' : 'error');
	}

	public function destroy(array $data): string
	{
		$id = $data['id'];

		$response = $this->db
			->select('movie_cover')
			->where('id_movie', $id)
			->limit(1)
			->get('cm_movies');

		if ($response->num_rows() == 0) {
			return 'not-found';
		}

		$movie = $response->row_array();

		$fDelete = $this->db->where('id_movie', $id)->delete('cm_movies');
		$sDelete = $this->db->where('id_movie', $id)->delete('cm_pro_mov');
		$tDelete = $this->db->where('id_movie', $id)->delete('cm_gen_mov');
		$lDelete = $this->db->where('id_movie', $id)->delete('cm_cat_mov');

		if (!$fDelete || !$sDelete || !$tDelete || !$lDelete) {
			return 'error';
		}

		if (strcmp($movie['movie_cover'], 'NO-IMAGE') != 0) {
			unlink($movie['movie_cover']);
		}
		return 'success';
	}

	public function search_results(array $builder = array()): object
	{
		$builder['columns'] = $builder['columns'] ?? 'id_movie, cm_movies.id_status, cm_status.status_name, cm_movies.id_quality, cm_qualities.quality_name, cm_movies.movie_name, cm_movies.movie_slug, cm_movies.movie_description, cm_movies.movie_release_date, cm_movies.movie_duration, cm_movies.movie_country_origin, cm_movies.movie_cover, cm_movies.movie_reproductions, cm_movies.movie_play, cm_movies.is_premiere, cm_movies.ip_registered_mov, cm_movies.date_registered_mov, cm_movies.client_registered_mov, cm_movies.ip_modified_mov, cm_movies.date_modified_mov, cm_movies.client_modified_mov';
		$builder['order_column'] = $builder['order_column'] ?? 'cm_movies.id_movie';
		$builder['order_filter'] = $builder['order_filter'] ?? 'DESC';
		$builder['start'] = $builder['start'] ?? 0;

		$response = $this->db
			->select($builder['columns'])
			->from('cm_movies')
			->join('cm_status', 'cm_status.id_status = cm_movies.id_status')
			->join('cm_qualities', 'cm_qualities.id_quality = cm_movies.id_quality');

		if (isset($builder['status'])) {
			$response = $response->where('cm_movies.id_status', 1);
		}

		$response = $response
			->like('LOWER(cm_movies.movie_name)', $builder['value'])
			->or_like('LOWER(cm_movies.movie_description)', $builder['value'])
			->order_by($builder['order_column'], $builder['order_filter']);

		if (isset($builder['limit'])) {
			$response = $response->limit($builder['limit'], $builder['start']);
		}

		$response = $response->get();

		return $response;
	}
}
