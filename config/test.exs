use Mix.Config

# Configure your database
config :doujor_api, DoujorApi.Repo,
  username: "postgres",
  password: "postgres",
  database: "doujor_api_test",
  hostname: "localhost",
  pool: Ecto.Adapters.SQL.Sandbox

# We don't run a server during test. If one is required,
# you can enable the server option below.
config :doujor_api, DoujorApiWeb.Endpoint,
  http: [port: 4002],
  server: false

# Print only warnings and errors during test
config :logger, level: :warn
