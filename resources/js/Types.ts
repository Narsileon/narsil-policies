export type AbilitiesType = {
	create: boolean;
	delete: boolean;
	update: boolean;
	view: boolean;
};

export type ModelHasPermissionModel = {
	id: number;
	model_id: number;
	model_type: string;
	permission_id: number;
	permission: PermissionModel;
};

export type ModelHasRoleModel = {
	id: number;
	model_id: number;
	model_type: string;
	role_id: number;
	role: RoleModel;
};

export type PermissionModel = {
	active: boolean;
	created_at: string;
	id: number;
	name: string;
	type: string;
	updated_at: string;
};

export type RoleModel = {
	active: boolean;
	created_at: string;
	id: number;
	label: string;
	level: number;
	name: string;
	updated_at: string;
};
