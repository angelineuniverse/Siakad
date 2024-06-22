import React, { Component, lazy } from "react";
import { ModelForm } from "./model";
import clsx from "clsx";
import Skeleton from "../../components/skeleton/skeleton";
import client from "../../service/service";

const Input = lazy(() => import("../../components/input/input"));
const Select = lazy(() => import("../../components/select/select"));
const SelectSearch = lazy(
  () => import("../../components/select-search/select_search")
);

class Form extends Component<ModelForm> {
  state: Readonly<{
    list: undefined | Array<any>;
    openList: boolean;
    search_value: undefined | string;
  }>;
  constructor(props: any) {
    super(props);
    this.state = {
      list: undefined,
      openList: false,
      search_value: undefined,
    };
    this.search = this.search.bind(this);
  }
  async search(value: any) {
    this.setState({
      search_value: value,
    });
    await client
      .get("/mahasiswa/search/find", {
        params: {
          name: value,
        },
      })
      .then((res) => {
        this.setState({
          openList: true,
          list: res.data.response_data,
        });
      });
  }

  render(): React.ReactNode {
    return (
      <div>
        {!this.props.form && (
          <div
            className={clsx(
              this.props.classNameLoading ?? "grid grid-cols-1 gap-x-2 gap-y-4"
            )}
          >
            {[...Array(this.props.lengthLoading ?? 5)].map(
              (item: any, index: number) => (
                <Skeleton type="input" key={index + "a"} />
              )
            )}
          </div>
        )}
        <div className={clsx(this.props.className)}>
          {this.props.form?.map((item: any) => (
            <div key={item.key}>
              {(() => {
                switch (item.type) {
                  case "password":
                  case "number":
                  case "text":
                  case "textarea":
                  case "upload":
                  case "time":
                  case "date":
                  case "datetime-local":
                  case "month":
                    return (
                      <Input
                        isRequired={item.isRequired}
                        placeholder={item.placeholder}
                        key={item.key}
                        description={item.description}
                        filename={item.filename}
                        readonly={item.readonly ?? false}
                        defaultValue={item[item.key ?? ""]}
                        label={item.label}
                        accept={item.accept}
                        autosize={item.autosize}
                        type={item.type}
                        onValueChange={(value: any) => {
                          item[item.key!] = value;
                        }}
                        preview_action={item.key}
                        preview={(value: any, key: string) => {
                          this.props.preview_file(key, value);
                        }}
                      />
                    );
                  case "select":
                    return (
                      <Select
                        isRequired={item.isRequired}
                        key={item.key}
                        placeholder={item.placeholder}
                        label={item.label}
                        onClick={(event: any) => {
                          item[item.key!] = event.target.value;
                        }}
                        keyValue={item.list?.keyValue}
                        keyoption={item.list?.keyoption}
                        options={item.list?.options}
                      />
                    );
                  case "select-search":
                    return (
                      <SelectSearch
                        isRequired={item.isRequired}
                        key={item.key}
                        readonly={item.readonly ?? false}
                        placeholder={item.placeholder}
                        label={item.label}
                        value={item[item.key] ?? undefined}
                        value_label={
                          this.state.search_value ?? item.value_label
                        }
                        openChildren={this.state.openList}
                        search_method={(event) => this.search(event)}
                      >
                        <div>
                          {this.state.list && this.state.openList && (
                            <div className="w-full border border-gray-300 rounded-md">
                              {this.state.list?.map((data) => (
                                <option
                                  className="px-3 py-2 selected hover:bg-blue-100 cursor-pointer text-xs"
                                  key={data[item.list.keyValue ?? ""]}
                                  value={data[item.list.keyValue ?? ""]}
                                  onClick={(event: any) => {
                                    this.props.remote_change(
                                      item.key,
                                      data[item.list.keyValue ?? ""]
                                    );
                                    item[item.key] = data[item.list.keyValue];
                                    this.setState({
                                      list: undefined,
                                      openList: false,
                                      search_value:
                                        data[item.list.keyoption] +
                                        " - " +
                                        data[item.list.keyprefix ?? ""],
                                    });
                                  }}
                                >
                                  {data[item.list.keyoption ?? ""]}{" "}
                                  {item.list.keyprefix && (
                                    <> - {data[item.list.keyprefix ?? ""]}</>
                                  )}
                                </option>
                              ))}
                            </div>
                          )}
                        </div>
                      </SelectSearch>
                    );
                  case "checkbox":
                  default:
                    return null;
                }
              })()}
            </div>
          ))}
        </div>
      </div>
    );
  }
}

export default Form;
