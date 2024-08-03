type ModelHasPermissionModel = {
	id: number;
	model_id: number;
	model_type: string;
	permission_id: number;
	permission: PermissionModel;
};

type ModelHasRoleModel = {
	id: number;
	model_id: number;
	model_type: string;
	role_id: number;
	role: RoleModel;
};

type PermissionModel = {
	active: boolean;
	created_at: string;
	id: number;
	name: string;
	type: string;
	updated_at: string;
};

type RoleModel = {
	active: boolean;
	created_at: string;
	id: number;
	label: string;
	level: number;
	name: string;
	updated_at: string;
};
