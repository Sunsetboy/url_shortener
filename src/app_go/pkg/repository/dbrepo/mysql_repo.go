package repository

import (
	"database/sql"
	"fmt"
)

type MysqlDBRepo struct {
	DB *sql.DB
}

type DatabaseRepo interface {
	Connection() *sql.DB
	// AddUrl(company data.Company) (data.Company, error)
	FetchAvailableCode() (string, error)
	// InsertUserLogin(userLogin data.UserLogin) (data.UserLogin, error)
	// FetchLastUserLogins(userIds []int, companyId int, limit int) ([]data.UserLogin, error)
}

func (m *MysqlDBRepo) Connection() *sql.DB {
	return m.DB
}

func (m *MysqlDBRepo) FetchAvailableCode() (string, error) {
	var code string
	var id int64
	row := m.DB.QueryRow("SELECT id, code FROM url_code WHERE is_used = 0 limit 1")
	if err := row.Scan(&id, &code); err != nil {
		if err == sql.ErrNoRows {
			return "", fmt.Errorf("no short URL codes available")
		}
		return "", fmt.Errorf("could not fetch available URL code: %v", err)
	}
	// flag the short URL code as used
	_, err := m.DB.Exec("UPDATE url_code SET is_used = 1 WHERE id = ? AND is_used = 0", id)
	if err != nil {
		return "", fmt.Errorf("could not update short URL record: %v", err)
	}

	return code, nil
}
