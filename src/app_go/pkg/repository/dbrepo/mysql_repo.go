package repository

import "database/sql"

type MysqlDBRepo struct {
	DB *sql.DB
}

func (m *MysqlDBRepo) Connection() *sql.DB {
	return m.DB
}

type DatabaseRepo interface {
	Connection() *sql.DB
	// AddUrl(company data.Company) (data.Company, error)
	// FetchAvailableCode(apiKey string) (data.Company, error)
	// InsertUserLogin(userLogin data.UserLogin) (data.UserLogin, error)
	// FetchLastUserLogins(userIds []int, companyId int, limit int) ([]data.UserLogin, error)
}
