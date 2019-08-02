defmodule DoujorApi.Repo do
  use Ecto.Repo,
    otp_app: :doujor_api,
    adapter: Ecto.Adapters.Postgres
end
